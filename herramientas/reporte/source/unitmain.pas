unit unitmain;

interface

uses
  Windows, SysUtils, Classes, Controls, Forms,
  IdBaseComponent, IdComponent, IdTCPServer,
  IdCustomHTTPServer, IdHTTPServer, IdContext, inifiles, superobject, idHttp,
  unittools;

type
  TRepThread = class(TThread)
  private
    QueryParams: string;
    url: string;
    Response: String;
    fDataText: String;
    qUrl: String;
  protected
    procedure Execute; override;
  end;

  TForm1 = class(TForm)
    idhtpsrvr1: TIdHTTPServer;
    procedure idhtpsrvr1CommandGet(AContext: TIdContext;
      ARequestInfo: TIdHTTPRequestInfo;
      AResponseInfo: TIdHTTPResponseInfo);
    procedure FormCreate(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure idhtpsrvr1CreatePostStream(AContext: TIdContext;
      var VPostStream: TStream);
  private
    Procedure ReadConfig;
  public
    { Public declarations }
  end;

var
  Form1: TForm1;

implementation

uses unitlog;

{$R *.dfm}

procedure TForm1.idhtpsrvr1CommandGet(AContext: TIdContext;
  ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo);
var
  json: TStringStream;
  s: ISuperObject;
  Rt: TRepThread;
  resp: string;
begin
  json := TStringStream.Create('');

  try
    ARequestInfo.PostStream.Position := 0;
    json.CopyFrom(ARequestInfo.PostStream, ARequestInfo.PostStream.Size);

    //======================== no se recibió texto post ========================

    if (json.DataString='') and (ARequestInfo.params.Values['url'] = '') then
    begin
      AResponseInfo.ContentType := 'text/plain';
      AResponseInfo.ContentText := '{"error":"No json received"}';
      AResponseInfo.WriteHeader;
      AResponseInfo.WriteContent;
      Exit;
    end;

    s := SO(UTF8ToAnsi(json.DataString));
    if (s = nil) then
    begin
      AResponseInfo.ContentType := 'text/plain';
      AResponseInfo.ContentText := '{"error":"No valid json received"}';
      AResponseInfo.WriteHeader;
      AResponseInfo.WriteContent;
      Exit;
    end;

    //--------- reporte -------

    Rt := TRepThread.Create(true);
    try
      Rt.QueryParams := ARequestInfo.QueryParams;
      Rt.url := ARequestInfo.Params.Values['url'];
     // Rt.FreeOnTerminate := true;
      Rt.Resume;
      resp := '';
      repeat
        resp := Rt.Response;
        Sleep(1000);
      until (resp<>'');
    finally
       FreeAndNil(Rt);
    end;
    AResponseInfo.ContentText := resp;
    AResponseInfo.ContentType := 'text/plain';

    //------------------------------
  finally
    json.Free;
  end;
end;

{ TRepThread }

procedure TRepThread.Execute;
var
  s: ISuperObject;
  infolder: string;
  rn: string;
  Start: cardinal;
  q: String;
  resp: string;
  fs: TFileStream;
begin
  inherited;
  try                                  //------- ejecucion principal
    Start := GetTickCount;
    q := QueryParams;

    //=================================== VERIFICAMOS EL JSON ==================
    if url <> '' then
    begin
(***********************************************************************************
                              VERIFICAMOS EL JSON
***********************************************************************************)
      repeat
        rn := RandomString;
        infolder := 'tmp\'+rn;
      until not DirectoryExists(ExtractFilePath(ParamStr(0))+'\'+infolder);
      ForceDirectories(ExtractFilePath(ParamStr(0))+'\'+infolder);

      //-- leer url pasando los parametros y guardar en archivo json
      qUrl := Copy(q,Pos('url=', q)+4,MaxInt);

      writelog('call url: '+qUrl, rn);

      try
        try

          fDataText := GetDosOutput(ExtractFilePath(ParamStr(0))+'curl -s "'+qUrl+'"');
          fDataText := Utf8ToAnsi(fDataText);

          if (fDataText='') then
          begin
            resp := '{"error":"Could not connect '+qUrl+'"}';
            exit;
          end;

          fs := TFileStream.Create( ExtractFilePath(ParamStr(0)) + 'tmp\'+rn+'\data.json', fmCreate );
          try
            fs.Write(fDataText[1], length(fDataText));
          finally
            fs.free;
          end;
        finally
          resp := GetDosOutput(ExtractFilePath(ParamStr(0)) + '\createpdf "'+ ExtractFilePath(ParamStr(0)) + 'tmp\'+rn+'" '+rn);
          s := SO(resp);
          if (s=nil) then resp := '{"error": "500"}';
        end;
      except
        resp := '{"error": "500"}';
      end;
    end;

(***********************************************************************************
                              FIN
***********************************************************************************)

  finally                              //------- fin
    s := nil;
    Response := resp;
    Terminate;
  end;
end;


procedure TForm1.FormCreate(Sender: TObject);
begin
(*  if (Now > EncodeDate(2022, 12, 31)) then
  begin
    halt(0);
    Abort;
  end;       *)
  randomize;
  readConfig;
  try
    idhtpsrvr1.Active := True;
    WriteLog('Conectado al puerto '+inttostr(idhtpsrvr1.defaultport));
  except
    on e:exception do
    begin
      WriteLog('Error al conectar: ' + e.Message);
    end;
  end;
end;

procedure TForm1.ReadConfig;
var
  fn: string;
  port: integer;
begin
  fn := ExtractFilePath(ParamStr(0)) + '\report.ini';
  with tinifile.Create(fn) do
  try
    port := ReadInteger('config','port',9997);
    WriteInteger('config', 'port', port);
    idhtpsrvr1.DefaultPort := port;
  finally
    Free;
  end;
end;

procedure TForm1.FormDestroy(Sender: TObject);
begin
  idhtpsrvr1.Active := false;
end;

procedure TForm1.idhtpsrvr1CreatePostStream(AContext: TIdContext;
  var VPostStream: TStream);
begin
  VPostStream := TMemoryStream.Create;
end;

end.
