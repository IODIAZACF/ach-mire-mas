unit unittools;

interface

uses Classes, SysUtils,
IdTCPConnection, IdTCPClient, IdHTTP,
windows;

function RandomString(cnt: Integer = 8): String;
function SaveFr3(fr3name, fr3content: String; sid: string=''): Boolean;
function GetDosOutput(CommandLine: string; Work: string = 'C:\'): string;
function downloadFile(url: string): TMemoryStream;

implementation

uses unitlog;

function RandomString(cnt: Integer = 8): String;
const
  arStr = '0123456789abcdef';
var
  i: integer;
  c: Byte;
begin
  result := '';
  for i:=0 to cnt - 1 do
  begin
    c := Random(15);
    Result := result + Copy(arStr,c,1);
  end;
end;

function SaveFr3(fr3name, fr3content: String; sid: string=''): Boolean;
var
  ss: TStringStream;
  fs: TFileStream;
  dr: string;
  nom: string;
begin
  result := True;
  nom := StringReplace(fr3name, '/', '\', [rfReplaceAll]);
  try
    ss := TStringStream.Create(UTF8Encode(fr3content));
    dr := ExtractFilePath(nom);
    ForceDirectories(dr);

    WriteLog('Saving to '+ dr + ExtractFileName(nom)+'.fr3', sid);

    fs := TFileStream.Create(dr + ExtractFileName(nom)+'.fr3', fmCreate);
    try
      try
        ss.Seek(0,0);
        fs.CopyFrom(ss, ss.Size);
      except
        on e:Exception do
        begin
          writelog(e.Message);
          result := false;
        end;
      end;
    finally
      fs.Free;
      ss.free;
    end;
  except
    on e:exception do
    begin
      writelog(e.Message);
      result := false;
    end;
  end;
end;

function GetDosOutput(CommandLine: string; Work: string = 'C:\'): string;
var
  SA: TSecurityAttributes;
  SI: TStartupInfo;
  PI: TProcessInformation;
  StdOutPipeRead, StdOutPipeWrite: THandle;
  WasOK: Boolean;
  Buffer: array[0..255] of AnsiChar;
  BytesRead: Cardinal;
  WorkDir: string;
  Handle: Boolean;
begin
  Result := '';
  with SA do begin
    nLength := SizeOf(SA);
    bInheritHandle := True;
    lpSecurityDescriptor := nil;
  end;
  CreatePipe(StdOutPipeRead, StdOutPipeWrite, @SA, 0);
  try
    with SI do
    begin
      FillChar(SI, SizeOf(SI), 0);
      cb := SizeOf(SI);
      dwFlags := STARTF_USESHOWWINDOW or STARTF_USESTDHANDLES;
      wShowWindow := SW_HIDE;
      hStdInput := GetStdHandle(STD_INPUT_HANDLE); // don't redirect stdin
      hStdOutput := StdOutPipeWrite;
      hStdError := StdOutPipeWrite;
    end;
    WorkDir := Work;
    Handle := CreateProcess(nil, PChar('cmd.exe /C ' + CommandLine),
                            nil, nil, True, 0, nil,
                            PChar(WorkDir), SI, PI);
    CloseHandle(StdOutPipeWrite);
    if Handle then
      try
        repeat
          WasOK := ReadFile(StdOutPipeRead, Buffer, 255, BytesRead, nil);
          if BytesRead > 0 then
          begin
            Buffer[BytesRead] := #0;
            Result := Result + Buffer;
          end;
        until not WasOK or (BytesRead = 0);
        WaitForSingleObject(PI.hProcess, INFINITE);
      finally
        CloseHandle(PI.hThread);
        CloseHandle(PI.hProcess);
      end;
  finally
    CloseHandle(StdOutPipeRead);
  end;
end;

function downloadFile(url: string): TMemoryStream;
var 
  MS: TMemoryStream;
  //http: TIdHTTP;
  fn: string;
  fs: TFileStream;
begin
  fn := ExtractFilePath(paramstr(0))+'\tmp\'+RandomString+'.bin';
  MS := TMemoryStream.Create;
  //http := TIdHTTP.Create;
  try
    MS.Clear;
    try
      //http.Get(Url, MS);
      GetDosOutput(ExtractFilePath(ParamStr(0))+'curl -s "'+url+'" -o "'+fn+'"');
      fs := TFileStream.Create(fn, fmOpenRead);
      try
        fs.seek(0,0);
        MS.CopyFrom(fs, fs.Size);
      finally
        fs.Free;
        DeleteFile(PChar(fn))
      end;
    except
      on e:exception do
      begin
        MS.Clear;
        WriteLog(e.message);
      end;
      //on E: EIdHTTPProtocolException do
      //  exit;
    end;
  finally
    //http.Disconnect;
    //http.Free;
    MS.Position := 0;
    result := MS;
    //MS.Free;
  end;
end;

end.
 