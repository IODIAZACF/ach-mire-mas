<?php
class class_tabpane{
      //propiedades del Acordion
             var $data;
             var $nombre;
      var $contenido_html;
      var $contenido_js;
      var $ancho;
      var $estilo;
      var $onSelect;


     function class_tabpane($id, $ancho=300, $alto=300)
     {
            $this->id        = $id;
            $this->ancho     = $ancho;
            $this->alto      = $alto;
            $this->estilo    = $estilo;
     }

     function armar()
     {

       $this->contenido_html .= '<div class="tab-pane" id="' . $this->id . '" style="width:' . $this->ancho . 'px;">'."\n";
           $this->contenido_js   .= 'o' . $this->id . ' = new WebFXTabPane( document.getElementById( "'. $this->id .'" ) );'."\n";
           $this->contenido_js   .= 'WebFXTabPane.bUseCookie = false;'."\n";
           $this->contenido_js   .= 'setupAllTabs();'."\n";
           if ($this->onSelect) $this->contenido_js .= 'o'.$this->id.'.onSelect = '.$this->onSelect.';'."\n";

       $contador = 0;
            while ($this->data['PANEL'.++$contador])
            {
                $this->contenido_html .= '<div class="tab-page" id="tabPage'.$contador.'" style="overflow:auto; height:'.$this->alto.'px;">';
                $this->contenido_html .= '<h2 class="tab">' . $this->data['PANEL' . $contador]['TITULO']. '</h2>';
                $this->contenido_html .= $this->data['PANEL'.$contador]['CONTENIDO'] . "\n";
                $this->contenido_html .= '</div>                                                                 '."\n";
            }

                   $this->contenido_html .= '</div>'."\n";
     }
}

?>