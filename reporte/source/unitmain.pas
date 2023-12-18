unit unitmain;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, frxClass, IdBaseComponent, IdComponent, IdTCPServer,
  IdCustomHTTPServer, IdHTTPServer, IdContext, JPEG, pngimage, GIFImage,
  unittools, superobject, RegExpr, frxExportBaseDialog, frxExportPDF,
  frxExportPDFHelpers, frxChart, frxGZip, frxDBSet, frxBarcode, frxCross,
  frxGaugeView, frxTableObject, frxMap, frxGradient, frxChBox, frxRich,
  frxOLE, idURI, inifiles, idHttp;

type
  TRepThread = class(TThread)
  private
    QueryParams: string;
    url: string;
    Response: String;
    fRep: TfrxReport;
    fImageList: TStringList;
    fCounts: TStringList;
    fDatasets: TList;
    SObj: ISuperObject;
    frxPDFExport1: TfrxPDFExport;
    UserDS: TfrxUserDataSet;
    xStart: Cardinal;
    xdn: String;
    xrn: string;
  protected
    procedure Execute; override;
    Function RepUserFunction(const MethodName: String;
      var Params: Variant): Variant;
    procedure RepBeforePrint(Sender: TfrxReportComponent);
    procedure RepGetValue(const VarName: String; var Value: Variant);
    procedure UserDSCheckEOF(Sender: TObject; var Eof: Boolean);
    procedure UserDSNewGetValue(Sender: TObject; const VarName: String;
      var Value: Variant);
    procedure UserDSFirst(Sender: TObject);
    procedure UserDSNext(Sender: TObject);
    procedure UserDSPrior(Sender: TObject);
    procedure CreatePDF;
  end;

  TForm1 = class(TForm)
    idhtpsrvr1: TIdHTTPServer;
    frxrprt1: TfrxReport;
    frxsrdtst1: TfrxUserDataSet;
    frxpdfxprt1: TfrxPDFExport;
    frxlbjct1: TfrxOLEObject;
    frxrchbjct1: TfrxRichObject;
    frxCheckBoxObject1: TfrxCheckBoxObject;
    frxGradientObject1: TfrxGradientObject;
    frxMapObject1: TfrxMapObject;
    frxReportTableObject1: TfrxReportTableObject;
    frxGaugeObject1: TfrxGaugeObject;
    frxCrossObject1: TfrxCrossObject;
    frxBarCodeObject1: TfrxBarCodeObject;
    frxDBDataset1: TfrxDBDataset;
    frxGZipCompressor1: TfrxGZipCompressor;
    frxChartObject1: TfrxChartObject;
    procedure idhtpsrvr1CommandGet(AContext: TIdContext;
      ARequestInfo: TIdHTTPRequestInfo;
      AResponseInfo: TIdHTTPResponseInfo);
    procedure idhtpsrvr1CreatePostStream(AContext: TIdContext;
      var VPostStream: TStream);
    procedure FormCreate(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
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
  fname: string;
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
    Rt.QueryParams := ARequestInfo.QueryParams;
    Rt.url := ARequestInfo.Params.Values['url'];
    Rt.FreeOnTerminate := true;
    Rt.Resume;
    resp := '';
    repeat
      resp := Rt.Response;
      Sleep(50);
    until (resp<>'');
    AResponseInfo.ContentText := resp;
    AResponseInfo.ContentType := 'text/plain';

    //------------------------------
  finally
    json.Free;
  end;
end;

{ TRepThread }

function FileSize(fileName : wideString) : Int64;
var
  sr : TSearchRec;
begin
  if FindFirst(fileName, faAnyFile, sr ) = 0 then
    result := Int64(sr.FindData.nFileSizeHigh) shl Int64(32) + Int64(sr.FindData.nFileSizeLow)
  else
    result := -1;
  FindClose(sr);
end;

procedure TRepThread.CreatePDF;
var
  fn: string;
  dn: string;
  rn: string;
  start: Cardinal;
  s: ISuperObject;
begin
  dn := xdn;
  rn := xrn;
  start := xStart;
  writelog('building report file');
  fn := dn+'\'+rn+'.pdf';
  s := SObj;
  
  if (s.S['variables.compress']='0') then
    frxPDFExport1.Compressed := false;

  try
    frxPDFExport1.FileName := fn;

    writelog('exporting to pdf file');

    try
      fRep.PrepareReport;
    except
      on e:Exception do
      begin
        Response := '{"error": "Error al generar reporte. Reintente!"}';
        writelog('Report not prepared: '+e.Message);
        Exit;
      end;
    end;
    writelog('Report prepared');

    while not fRep.Export(frxPDFExport1) and (filesize(fn)<1024) do
    begin
      Sleep(100);
    end;    

    WriteLog('Done! '+fn);
  except
    on e:Exception do
    begin
      writelog('Error: '+e.Message);
    end;
  end;

  s                 := SO;
  s.S['error']      := '';
  s.S['pdf']        := 'pdf/'+extractFileName(fn);
  s.I['timesec']    := (GetTickCount - Start) div 1000;
  Response := s.AsJSon(True, False);
end;

procedure TRepThread.Execute;
var
  i: integer;
  s: ISuperObject;
  du: TfrxUserDataset;
  infolder: string;
  item: TSuperObjectIter;
  k: integer;
  mainRep: string;
  fn, rn, dn: string;
  oValue: string;
  Start: cardinal;
  txt: string;
  q: String;
  fx: TFileStream;
  Uri: TIdURI;
  urlfr3: string;
  pdfCreated: Boolean;
  resp: string;
begin
  inherited;
  fRep := TfrxReport.Create(nil);
  fRep.EngineOptions.DoublePass           := True;
  fRep.EngineOptions.UseGlobalDataSetList := False;
  fRep.EngineOptions.EnableThreadSafe     := True;
  fRep.EngineOptions.DestroyForms         := False;
  fRep.EngineOptions.IgnoreExprError      := True;
  fRep.AddFunction('function UTF8(s: String):String');
  fRep.EngineOptions.NewSilentMode := simSilent; //simReThrow;//simReThrow; //simMessageBoxes; //simSilent; //simReThrow;
  fRep.EngineOptions.SilentMode := True;

  fRep.OnBeforePrint  := RepBeforePrint;
  fRep.OnUserFunction := RepUserFunction;
  fRep.OnGetValue     := RepGetValue;
  
  fDatasets  := TList.Create;
  fCounts    := TStringList.Create;
  FImagelist := TStringList.Create;

  frxPDFExport1 := TfrxPDFExport.Create(nil);
  frxPDFExport1.Author := 'ImagiX';
  frxPDFExport1.Compressed := true;
  frxPDFExport1.Creator := 'ImagiX';
  frxPDFExport1.EmbeddedFonts := True;
  frxPDFExport1.OpenAfterExport := false;
  frxPDFExport1.ShowDialog  := false;
  frxPDFExport1.ShowProgress := false;
  frxPDFExport1.HideWindowUI := True;
  frxPDFExport1.HTMLTags := True;
  frxPDFExport1.PdfA := True;
  frxPDFExport1.PDFStandard := psPDFA_2a;
  frxPDFExport1.PDFVersion := pv17;
  frxPDFExport1.Subject := 'Reporting Tool';
  frxPDFExport1.UseFileCache := false;

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
      q := Copy(q,Pos('url=', q)+4,MaxInt);

      writelog('call url: '+q);

      try
        txt := GetDosOutput(ExtractFilePath(ParamStr(0))+'curl -s "'+q+'"');
        txt := Utf8ToAnsi(txt);

        if (txt='') then
        begin
          Response := '{"error":"Could not connect '+q+'"}';
          exit;
        end;
      finally
      end;
    
      try
        try
          SObj := SO(txt);
          if SObj = nil then
          begin
            SObj := SO;
            SObj.S['error'] := '502';
            SObj.S['file']  := ExtractFilePath(ParamStr(0))+'\'+infolder+'\data.json';
            Response := SObj.AsJSon;
            exit;
          end;
        except
          on e:Exception do
          begin
            WriteLog(e.Message);
            Response := '{"error": "502"}';
            Exit;
          end;
        end;
        s := SObj;
      finally
        //SObj := nil;
      end;
    end;

    resp := '{"error": "500"}';

    try
      try
        //writelog('init datasets');
        //Result := AnsiToUtf8(s.AsJSon(True, False));

(***********************************************************************************
                              CARGA DE LOS DATASETS
***********************************************************************************)

        if ObjectFindFirst(s.O['datasets'], item) then
        repeat

          if (not item.val.IsType(stObject)) then
            Continue;

          du := TfrxUserDataSet.Create(nil);

          du.OnNewGetValue := UserDSNewGetValue;
          du.OnFirst       := UserDSFirst;
          du.OnCheckEOF    := UserDSCheckEOF;
          du.OnNext        := UserDSNext;
          du.OnPrior       := UserDSPrior;
          du.UserName      := item.key;

          du.Fields.Clear;

          for k:=0 to s.O['datasets'].O[item.key].A['fields'].Length-1 do
            du.Fields.Add(s.O['datasets'].O[item.key].A['fields'].S[k]);

          fCounts.Values[item.key] := IntToStr(s.O['datasets'].O[item.key].A['data'].Length);

          fDataSets.Add(du);
          fRep.DataSets.Add(du);
          fRep.EnabledDataSets.add(du);
        until not ObjectFindNext(item);
        ObjectFindClose(item);

        writelog('get report format');
        //===================== CREO LA CARPETA ALEATORIA ==========================
        repeat
          rn := RandomString;
          infolder := 'tmp\'+rn;
        until not DirectoryExists(ExtractFilePath(ParamStr(0))+'\'+infolder);
        ForceDirectories(ExtractFilePath(ParamStr(0))+'\'+infolder);

(***********************************************************************************
                              GUARDO LAS PLANTILLAS
***********************************************************************************)

        writelog('init templates');

        if ObjectFindFirst(s.O['templates'], item) then
        begin
          repeat
            if UpperCase(Copy(item.val.AsString,1,4)) = 'HTTP' then
            begin
              uri := TIdURI.Create(q);
              //url := stringreplace(item.val.AsString, '<host>', uri.Host+':'+inttostr(Uri.port), [rfreplaceAll,rfignorecase]);
              urlfr3 := StringReplace(item.val.AsString, '<host>', uri.Host+':'+Uri.port, [rfreplaceAll,rfignorecase]);
              fx := TFileStream.Create(ExtractFilePath(ParamStr(0))+infolder + '\' + item.key +'.fr3', fmCreate);
              with TIdHTTP.Create do
              try
                fx.Seek(0,0);
                writelog('Downloading template from '+urlfr3);
                Get(urlfr3, fx);
              finally
                fx.Free;
                free;
              end;
            end
            else SaveFr3(infolder + '\'+ item.key, item.val.AsString);
          until not ObjectFindNext(item);
          ObjectFindClose(item);
        end;

(***********************************************************************************
                        GUARDO LOS REPORTES PRINCIPALES
***********************************************************************************)

        writelog('init main report format');
        mainRep := '';

        //WriteLog(s.AsJSon(true));
        if ObjectFindFirst(s.O['reports'], item) then
        begin
          repeat
            if UpperCase(Copy(item.val.AsString,1,4)) = 'HTTP' then
            begin
              uri := TIdURI.Create(q);
              urlfr3 := StringReplace(item.val.AsString, '<host>', uri.Host+':'+Uri.port, [rfreplaceAll,rfignorecase]);
              fx := TFileStream.Create(ExtractFilePath(ParamStr(0))+infolder + '\' + item.key +'.fr3', fmCreate);
              with TIdHTTP.Create do
              try
                fx.Seek(0,0);
                writelog('Downloading report from '+urlfr3);
                Get(urlfr3, fx);
                mainRep := infolder + '\' + item.key;
              finally
                fx.Free;
                free;
              end;
            end
            else
            begin
              WriteLog('Trying to save...');
              if SaveFr3(infolder + '\'+ item.key, item.val.AsString) then
              begin
                mainRep := StringReplace(infolder + '\'+ item.key,'/','\',[rfReplaceAll]);
              end;
            end;
          until not ObjectFindNext(item);
          ObjectFindClose(item);
        end;

        if mainRep='' then
        begin
          response := '{"error": "La data fr3 no existe en el JSON recibido!"}';
          Exit;
        end;

(***********************************************************************************
              AL HABER REPORTE ENTONCES SE PROCEDE
***********************************************************************************)

        //======= cargo el FR3 =========

        writelog('loading report');

        fn := ExtractFilePath(ParamStr(0))+mainRep+'.fr3';
        try
          if fileExists(fn) then
            fRep.LoadFromFile(fn);
        except
          on e:Exception do
          begin
            s := SO;
            s.S['error'] := e.Message;
            s.S['json']  := AnsiToUtf8(s.AsJSon(True, False));
            Response := s.AsJSon(True, false);
            s := nil;
            exit;
          end;
        end;

        //======= cargo las variables =========

        writelog('set variables');

        fRep.Variables.BeginUpdate;
        try
          if (s.S['variables']<>'') then
          begin
            if ObjectFindFirst(s.O['variables'], item) then
            try
              repeat
                oValue := s.S['variables.'+item.key];
                if oValue='null' then
                  oValue := '';
                if (item.val.DataType in [stInt, stDouble]) then
                  fRep.Variables.AddVariable('Globales', item.key, oValue)
                else
                begin
                  oValue := StringReplace(oValue, sLineBreak, '\n', [rfReplaceAll]);
                  oValue := StringReplace(oValue, #10, '\n', [rfReplaceAll]);
                  if (Pos('\n', oValue)>0) then
                  begin
                    oValue := StringReplace(oValue, '\n', sLineBreak, [rfReplaceAll]);
                    fRep.Variables.AddVariable('Globales', item.key, oValue);
                  end
                  else
                  begin
                    oValue := StringReplace(oValue, '\n', sLineBreak, [rfReplaceAll]);
                    fRep.Variables.AddVariable('Globales', item.key, ''''+oValue+'''');
                  end;
                end;
              until not ObjectFindNext(item);
              ObjectFindClose(item);
            except
              on e:exception do
              begin
                WriteLog(e.Message);
              end;
            end;
          end;
        finally
          fRep.Variables.EndUpdate;
        end;

        //================= creo la carpeta pdf si no existe ===================

        dn := ExtractFilePath(ParamStr(0))+'pdf';
        ForceDirectories(dn);

(***********************************************************************************
                                PREPARO EL PDF
***********************************************************************************)
        xdn := dn;
        xrn := rn;
        xStart := Start;

        Synchronize(CreatePDF);
        
        Resp := Response;
      except
        on e:exception do
        begin
          resp := '{"error": "'+e.message+'"}';
        end;
      end;
    finally
      s := nil;
      Response := resp;
    end;

(***********************************************************************************
                              FIN
***********************************************************************************)

  finally                              //------- fin
    fRep.Variables.Clear;
    SObj := nil;
    frxPDFExport1.Free;
    fImagelist.Free;
    fCounts.Free;
    fRep.DataSets.Clear;
    fRep.EnabledDataSets.Clear;
    for i:=fDatasets.Count-1 downto 0 do
    begin
      TfrxUserDataSet(fDatasets[i]).Close;
      TfrxUserDataSet(fDatasets[i]).Clear;
      TfrxUserDataSet(fDatasets[i]).Free;
    end;
    fDatasets.Clear;
    fDatasets.Free;
    fRep.free;
    Terminate;
    WaitFor;
    FreeAndNil(self);
  end;
end;

procedure TRepThread.RepBeforePrint(Sender: TfrxReportComponent);
var
  ext: string;
  url: string;
  jpg: TJPEGImage;
  png: TPNGObject;
  gif: TGIFImage;
  ms: TMemoryStream;
  fn: string;
begin
  if Sender.ClassName = 'TfrxPictureView' then
  begin
    if fImageList.IndexOf(Sender.name)>=0 then
      exit;

    fImageList.Add(Sender.name);

    url := TfrxPictureView(fRep.FindComponent(Sender.name)).tagstr;
    if url <> '' then
    try
      ext := LowerCase(ExtractFileExt(url));
      if (ext='.jpg') or (ext='.jpeg') then
      begin
        jpg := TJPEGImage.Create;
        ms := downloadFile(url);
        try
          jpg.LoadFromStream(ms);
          //Image1.Picture.Assign(jpg);
          //TfrxPictureView(Rep.FindComponent(Sender.name)).Picture.Assign(Image1.Picture);
          TfrxPictureView(fRep.FindComponent(Sender.name)).Picture.Assign(jpg);
        finally
          ms.Free;
          jpg.Free;
        end;
      end
      else if (ext='.png') then
      begin
        png := TPNGObject.Create;
        ms := downloadFile(url);
        try
          try
            png.LoadFromStream(ms);
          except
            png.Assign(nil);
          end;
          TfrxPictureView(fRep.FindComponent(Sender.name)).Picture.Assign(png);
        finally
          ms.Free;
          png.free;
          if (FileExists(fn)) then
          begin
            DeleteFile(fn);
          end;
        end;
      end
      else if (ext='.gif') then
      begin
        gif := TGIFImage.Create;
        ms := downloadFile(url);
        try
          gif.LoadFromStream(ms);
          TfrxPictureView(fRep.FindComponent(Sender.name)).Picture.Assign(gif);
        finally
          ms.Free;
          gif.Free;
        end;
      end;
    except
      TfrxPictureView(fRep.FindComponent(Sender.name)).Picture.Assign(nil);
    end;
  end;
  //ShowMessage(Sender.ClassName);
end;

procedure TRepThread.RepGetValue(const VarName: String;
  var Value: Variant);
var
  v: string;
  fs: TFormatSettings;
begin
  fs.ShortTimeFormat := 'hh:nn';
  fs.LongTimeFormat := 'hh:nn:ss';
  fs.ShortDateFormat := 'yyyy-mm-dd';
  fs.DateSeparator := '-';
  fs.TimeSeparator := ':';
  v := SObj.O['variables'].S[VarName];
  if v <> '' then
  begin
    if ExecRegExpr ('^\d{4}-\d{2}-\d{2}(\s\d{2}\:\d{2}\:\d{2}|)', v) then
    begin
      value := StrToDateTime(v, fs);
    end;
  end;
end;

function TRepThread.RepUserFunction(const MethodName: String;
  var Params: Variant): Variant;
begin
  if UpperCase(MethodName) = 'UTF8' then
  begin
    Result := UTF8Encode(Params[0]);
  end;
end;

procedure TRepThread.UserDSCheckEOF(Sender: TObject; var Eof: Boolean);
var
  mx: integer;
begin
  mx := StrToIntDef(fCounts.Values[TfrxUserDataSet(Sender).UserName],0);
  Eof := TfrxUserDataSet(Sender).Tag >= mx;
  if Eof then
    TfrxUserDataSet(Sender).Tag := mx-1;
end;

procedure TRepThread.UserDSFirst(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := 0;
end;

procedure TRepThread.UserDSNewGetValue(Sender: TObject;
  const VarName: String; var Value: Variant);
var
  i: integer;
  rec: integer;
  fn: string;
  t: string;
  fs: TFormatSettings;
  oValue: string;
  s: ISuperObject;
begin
  fs.ShortTimeFormat := 'hh:mm';
  fs.LongTimeFormat  := 'hh:mm:ss';
  fs.DateSeparator   := '-';
  fs.TimeSeparator   := ':';
  ThousandSeparator  := '.';

  s := SObj;
  rec := TfrxUserDataset(sender).tag;

  for i:=0 to s.O['datasets'].O[TfrxUserDataset(sender).UserName].A['fields'].Length-1 do
  begin
    if s.O['datasets'].O[TfrxUserDataset(sender).UserName].A['fields'].S[i] = VarName then
    begin
      t := s.O['datasets'].O[TfrxUserDataset(sender).UserName].A['types'].S[i];
      fn := 'datasets.'+TfrxUserDataset(sender).UserName+'.data['+inttostr(rec)+']['+inttostr(i)+']';

      oValue := s.S[fn];
      if oValue = 'null' then
        oValue := '';

      if (t='C') or (t='X') then
      begin
        Value := oValue;
      end
      else if T='I' then
      begin
        if (oValue='') then
          value := ''
        else
          Value := s.I[fn];
      end
      else if (t='D') or (t='T') then
      begin
        if (oValue='') then
        begin
          value := '';
          break;
        end;

        if (StrToIntDef(Copy(oValue,1,4),-1)>0) then
        begin
          fs.ShortDateFormat := 'yyyy-mm-dd';
          fs.LongTimeFormat := 'HH:nn:ss';
          fs.DateSeparator := '-';
        end
        else
        begin
          fs.ShortDateFormat := 'mm/dd/yyyy';
          fs.LongTimeFormat := 'HH:nn:ss';
          fs.DateSeparator := '/';
        end;
        Value := VarFromDateTime(StrToDateTime(s.S[fn], fs));
        //Value := FormatDateTime('dd/mm/yyyy',StrToDateTime(s.S[fn], fs));
      end
      else if (t='N') then
      begin
        if (oValue='') then
          value := ''
        else
          value := s.D[fn];
      end;
      break;
    end
  end;
end;

procedure TRepThread.UserDSNext(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := TfrxUserDataSet(Sender).Tag + 1;
end;

procedure TRepThread.UserDSPrior(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := TfrxUserDataSet(Sender).Tag - 1;
end;

procedure TForm1.idhtpsrvr1CreatePostStream(AContext: TIdContext;
  var VPostStream: TStream);
begin
  VPostStream := TMemoryStream.Create;
end;

procedure TForm1.FormCreate(Sender: TObject);
begin
  if (Now > EncodeDate(2022, 12, 31)) then
  begin
    halt(0);
    Abort;
  end;          
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

end.
