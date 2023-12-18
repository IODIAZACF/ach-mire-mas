unit unitlog;

interface

uses Classes, sysutils;

procedure WriteLog(s: String; sid: string = '');

implementation

procedure WriteLog(s: String; sid: string = '');
var
  fs: TFileStream;
  pth: string;
  dt: TDateTime;
  fn: string;
  fm: Integer;
  msg : string;
begin
  //exit;
  dt := Now;
  pth := ExtractFilePath(paramstr(0));
  ForceDirectories(pth+'\log');

  fn := pth+'\log\'+FormatDateTime('yyy-mm-dd', dt)+'.log';
  fm := fmCreate;
  if FileExists(fn) then
    fm := fmOpenWrite + fmShareDenyNone;

  if (sid <> '') then
    s := '['+sid+'] '+s;
    
  fs := TFileStream.Create(fn, fm);
  try
    fs.Seek(0,soFromEnd);
    s := FormatDateTime('hh:nn:ss -> ', dt) + s + sLineBreak;
    fs.Write(s[1], length(s));
  finally
    fs.Free;
  end;
end;

end.
