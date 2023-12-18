object Form1: TForm1
  Left = 414
  Top = 275
  Width = 350
  Height = 305
  Caption = 'Reporter 2022-2'
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  OnCreate = FormCreate
  OnDestroy = FormDestroy
  PixelsPerInch = 96
  TextHeight = 13
  object idhtpsrvr1: TIdHTTPServer
    Bindings = <>
    DefaultPort = 9997
    OnCreatePostStream = idhtpsrvr1CreatePostStream
    OnCommandGet = idhtpsrvr1CommandGet
    Left = 152
    Top = 48
  end
end
