program createpdf;

{$APPTYPE CONSOLE}

uses
  SysUtils,
  classes,
  superobject,
  JPEG, pngimage, GIFImage, frxClass,
  RegExpr, frxExportBaseDialog, frxExportPDF,
  frxExportPDFHelpers, frxChart, frxGZip, frxDBSet, frxBarcode, frxCross,
  frxGaugeView, frxTableObject, frxMap, frxGradient, frxChBox, frxRich,
  frxOLE, idURI, IdHTTP, unittools, unitlog, windows, variants;

type
  TOblClass = class
    procedure RepBeforePrint(Sender: TfrxReportComponent);
    Function RepUserFunction(const MethodName: String;
      var Params: Variant): Variant;
    procedure RepGetValue(const VarName: String; var Value: Variant);
    procedure UserDSNewGetValue(Sender: TObject; const VarName: String;
      var Value: Variant);
    procedure UserDSFirst(Sender: TObject);
    procedure UserDSCheckEOF(Sender: TObject; var Eof: Boolean);
    procedure UserDSNext(Sender: TObject);
    procedure UserDSPrior(Sender: TObject);
  end;

var
  i: integer;
  sl : TStringList;
  fn: string;
  xfn: string;
  json: string;
  s: ISuperObject;
                   
  obj: TOblClass;
  fRep: TfrxReport;
  fDatasets: TList;
  fCounts: TStringList;
  FImagelist: TStringList;
  frxPDFExport1: TfrxPDFExport;
  item: TSuperObjectIter;
  du: TfrxUserDataSet;
  K: Integer;
  uri: TIdURI;
  urlfr3: string;
  fx: TFileStream;
  infolder: string;
  mainrep: string;
  oValue: string;
  dn: string;
  resp: string;
  Start: Cardinal;
  rn: string;

{ TOblClass }

function FileSize(fileName : wideString) : Int64;
var
  sr : TSearchRec;
begin
  if FindFirst(fileName, faAnyFile, sr ) = 0 then
    result := Int64(sr.FindData.nFileSizeHigh) shl Int64(32) + Int64(sr.FindData.nFileSizeLow)
  else
    result := -1;
  Sysutils.FindClose(sr);
end;

procedure TOblClass.RepBeforePrint(Sender: TfrxReportComponent);
var
  ext: string;
  url: string;
  jpg: TJPEGImage;
  png: TPNGObject;
  gif: TGIFImage;
  ms: TMemoryStream;
//  fn: string;
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
          (*if (FileExists(fn)) then
          begin
            DeleteFile(fn);
          end;     *)
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

procedure TOblClass.RepGetValue(const VarName: String; var Value: Variant);
var
  v: string;
  fs: TFormatSettings;
begin
  fs.ShortTimeFormat := 'hh:nn';
  fs.LongTimeFormat := 'hh:nn:ss';
  fs.ShortDateFormat := 'yyyy-mm-dd';
  fs.DateSeparator := '-';
  fs.TimeSeparator := ':';
  v := s.O['variables'].S[VarName];
  if v <> '' then
  begin
    if ExecRegExpr ('^\d{4}-\d{2}-\d{2}(\s\d{2}\:\d{2}\:\d{2}|)', v) then
    begin
      value := StrToDateTime(v, fs);
    end;
  end;
end;

function TOblClass.RepUserFunction(const MethodName: String;
  var Params: Variant): Variant;
begin
  if UpperCase(MethodName) = 'UTF8' then
  begin
    Result := UTF8Encode(Params[0]);
  end;
end;

procedure TOblClass.UserDSCheckEOF(Sender: TObject; var Eof: Boolean);
var
  mx: integer;
begin
  mx := StrToIntDef(fCounts.Values[TfrxUserDataSet(Sender).UserName],0);
  Eof := TfrxUserDataSet(Sender).Tag >= mx;
  if Eof then
    TfrxUserDataSet(Sender).Tag := mx-1;
end;

procedure TOblClass.UserDSFirst(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := 0;
end;

procedure TOblClass.UserDSNewGetValue(Sender: TObject;
  const VarName: String; var Value: Variant);
var
  i: integer;
  rec: integer;
  fn: string;
  t: string;
  fs: TFormatSettings;
  oValue: string;
  //s: ISuperObject;
begin
  fs.ShortTimeFormat := 'hh:mm';
  fs.LongTimeFormat  := 'hh:mm:ss';
  fs.DateSeparator   := '-';
  fs.TimeSeparator   := ':';
  ThousandSeparator  := '.';

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

procedure TOblClass.UserDSNext(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := TfrxUserDataSet(Sender).Tag + 1;
end;

procedure TOblClass.UserDSPrior(Sender: TObject);
begin
  TfrxUserDataSet(Sender).Tag := TfrxUserDataSet(Sender).Tag - 1;
end;

//==============================================================================
//                                M A I N
//==============================================================================
begin
  Randomize;
  Start := GetTickCount;

  fn := ParamStr(1) + '\data.json';
  rn := ParamStr(2);
  if not FileExists(fn) then
  begin
    System.Writeln('{"error": "no json input file defined"}');
    Halt(0);
  end;

  //----- cargo la data

  sl := TStringList.Create;
  try
    sl.LoadFromFile(fn);
    json := sl.Text;
  finally
    sl.Free;
  end;

  try
    try
      obj := TOblClass.Create;
      s := SO(json);
      if (s = nil) then
      begin
        System.Writeln('{"error": "no valid json structure"}');
        Halt(0);
      end;

      fRep := TfrxReport.Create(nil);
      fRep.EngineOptions.DoublePass           := True;
      fRep.EngineOptions.UseGlobalDataSetList := False;
      fRep.EngineOptions.EnableThreadSafe     := True;
      fRep.EngineOptions.DestroyForms         := False;
      fRep.EngineOptions.IgnoreExprError      := True;
      fRep.AddFunction('function UTF8(s: String):String');
      fRep.EngineOptions.NewSilentMode := simSilent; //simReThrow;//simReThrow; //simMessageBoxes; //simSilent; //simReThrow;
      fRep.EngineOptions.SilentMode := True;

      fRep.OnBeforePrint  := obj.RepBeforePrint;
      fRep.OnUserFunction := obj.RepUserFunction;
      fRep.OnGetValue     := obj.RepGetValue;
  
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

            du.OnNewGetValue := obj.UserDSNewGetValue;
            du.OnFirst       := obj.UserDSFirst;
            du.OnCheckEOF    := obj.UserDSCheckEOF;
            du.OnNext        := obj.UserDSNext;
            du.OnPrior       := obj.UserDSPrior;
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

          writelog('get report format', rn);

  (***********************************************************************************
                                GUARDO LAS PLANTILLAS
  ***********************************************************************************)

          writelog('init templates', rn);

          infolder := ParamStr(1);

          if ObjectFindFirst(s.O['templates'], item) then
          begin
            repeat
              if UpperCase(Copy(item.val.AsString,1,4)) = 'HTTP' then
              begin
                uri := TIdURI.Create(s.S['url']);
                urlfr3 := StringReplace(item.val.AsString, '<host>', uri.Host+':'+Uri.port, [rfreplaceAll,rfignorecase]);
                fx := TFileStream.Create(infolder + '\' + item.key +'.fr3', fmCreate);
                with TIdHTTP.Create do
                try
                  fx.Seek(0,0);
                  writelog('Downloading template from '+urlfr3, rn);
                  Get(urlfr3, fx);
                finally
                  fx.Free;
                  Uri.free;
                end;
              end
              else SaveFr3(infolder + '\'+ item.key, item.val.AsString, rn);
            until not ObjectFindNext(item);
            ObjectFindClose(item);
          end;

  (***********************************************************************************
                          GUARDO LOS REPORTES PRINCIPALES
  ***********************************************************************************)

          writelog('init main report format', rn);
          mainRep := '';

          //WriteLog(s.AsJSon(true));
          if ObjectFindFirst(s.O['reports'], item) then
          begin
            xfn := StringReplace(ExtractFileName(item.key),'/','-',[rfReplaceAll]);
            repeat
              if UpperCase(Copy(item.val.AsString,1,4)) = 'HTTP' then
              begin
                uri := TIdURI.Create(s.S['url']);
                urlfr3 := StringReplace(item.val.AsString, '<host>', uri.Host+':'+Uri.port, [rfreplaceAll,rfignorecase]);
                fx := TFileStream.Create(infolder + '\' + xfn +'.fr3', fmCreate);
                with TIdHTTP.Create do
                try
                  fx.Seek(0,0);
                  writelog('Downloading report from '+urlfr3, rn);
                  Get(urlfr3, fx);
                  mainRep := infolder + '\' + xfn;
                finally
                  fx.Free;
                  free;
                end;
              end
              else
              begin
                WriteLog('Trying to save...', rn);
                if SaveFr3(infolder + '\'+ xfn, item.val.AsString, rn) then
                begin
                  mainRep := StringReplace(infolder + '\'+ xfn,'/','\',[rfReplaceAll]);
                end;
              end;
            until not ObjectFindNext(item);
            ObjectFindClose(item);
          end;

          if mainRep='' then
          begin
            resp := '{"error": "La data fr3 no existe en el JSON recibido!"}';
            Exit;
          end;

  (***********************************************************************************
                AL HABER REPORTE ENTONCES SE PROCEDE
  ***********************************************************************************)

          //======= cargo el FR3 =========

          writelog('loading report', rn);

          xfn := mainRep+'.fr3';
          try
            if fileExists(xfn) then
              fRep.LoadFromFile(xfn);
          except
            on e:Exception do
            begin
              s := SO;
              s.S['error'] := e.Message;
              s.S['json']  := AnsiToUtf8(s.AsJSon(True, False));
              System.WriteLn(s.AsJSon(True, false));
              s := nil;
              exit;
            end;
          end;

          //======= cargo las variables =========

          writelog('set variables', rn);

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
                  WriteLog(e.Message, rn);
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

          //rn := RandomString;
          writelog('building report file', rn);
          fn := dn+'\'+rn+'.pdf';
          //s := SObj;

          if (s.S['variables.compress']='0') then
            frxPDFExport1.Compressed := false;

          try
            frxPDFExport1.FileName := fn;

            writelog('exporting to pdf file', rn);

            try
              fRep.PrepareReport;
            except
              on e:Exception do
              begin
                System.Writeln('{"error": "Error al generar reporte. Reintente!"}');
                writelog('Report not prepared: '+e.Message, rn);
                Exit;
              end;
            end;
            writelog('Report prepared', rn);

            while not fRep.Export(frxPDFExport1) and (filesize(fn)<1024) do
            begin
              Sleep(100);
            end;    

            WriteLog('Done! '+fn, rn);
          except
            on e:Exception do
            begin
              writelog('Error: '+e.Message, rn);
            end;
          end;

          s                 := SO;
          s.S['error']      := '';
          s.S['pdf']        := 'pdf/'+extractFileName(fn);
          s.I['timesec']    := (GetTickCount - Start) div 1000;
          resp := s.AsJSon(True, False);

        except
          on e:exception do
          begin
            resp := '{"error": "'+e.message+'"}';
          end;
        end;
      finally
        fRep.Variables.Clear;
        S := nil;
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
        obj.Free;
      end;

    except

    end;
  finally
    System.Writeln(resp);
  end;
  //Readln;
end.
