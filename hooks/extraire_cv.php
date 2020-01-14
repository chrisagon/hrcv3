<?php
/* Script d'extraction du CV pour alimenter les missions et les compétences */
    class DocxConversion
    {
        private $filename;

        public function __construct( $filePath )
        {
            $this->filename = $filePath;
        }

        private function lire_doc()
        {
            $fileHandle = fopen( $this->filename, "r" );
            $line       = @fread( $fileHandle, filesize( $this->filename ) );
            $lines      = explode( chr( 0x0D ), $line );
            $outtext    = "";

            foreach ( $lines as $thisline ) {
                $pos = strpos( $thisline, chr( 0x00 ) );

                if (  ( $pos !== false ) || ( strlen( $thisline ) == 0 ) ) {
                } else {
                    $outtext .= $thisline . " ";
                }
            }

            $outtext =
                preg_replace( "/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext );
            return $outtext;
        }

        private function lire_docx()
        {

            $striped_content = '';
            $content         = '';

            $zip = zip_open( $this->filename );

            if ( !$zip || is_numeric( $zip ) ) {
                return false;
            }
            while ( $zip_entry = zip_read( $zip ) ) {
                if ( zip_entry_open( $zip, $zip_entry ) == false ) {
                    continue;
                }

                if ( zip_entry_name( $zip_entry ) != "word/document.xml" ) {
                    continue;
                }

                $content .= zip_entry_read( $zip_entry,
                    zip_entry_filesize( $zip_entry ) );

                zip_entry_close( $zip_entry );
            }
    // end while

            zip_close( $zip );
    /* Identification des balises d'extractions  */
/*            $content = stripAccents($content); */
// nettoyage de chaines inutiles pour les titres et balises
            $content = str_ireplace('<w:szCs w:val="24"/>', "", $content);
            $content = str_ireplace('<w:sz w:val="24"/>', "", $content);
            $content = str_ireplace('<w:rFonts w:cs="Arial"/>', "", $content);
            $content = str_ireplace('<w:lang w:val="fr-FR" w:eastAsia="fr-FR"/>', "", $content);
            $content = str_ireplace('<w:lang w:val="en-US"/>', "", $content);
            $content = str_ireplace('<w:keepNext/>', "", $content);
            $content = str_ireplace('<w:lastRenderedPageBreak/>', "", $content);
            $content = str_ireplace('<w:proofErr w:type="spellStart"/>', "", $content);
            $content = str_ireplace('<w:proofErr w:type="spellEnd"/>', "", $content);
            $content = str_ireplace('<w:ind w:left="0" w:firstLine="0"/>', "", $content);
//            $content = preg_replace('#<w:rPr><w:rFonts w(.+)/></w:rPr>#isx', "#",$content);
            $content = preg_replace('#\<w:rpr\><w:rfonts (.+?)\<\/w:rpr\>#isx', "",$content);
            $content = preg_replace('/w:pos="..."/', "",$content);
            $content = preg_replace('/w:left="..."/', "",$content);
            $content = preg_replace('/w:hanging="..."/', "",$content);
            $content = preg_replace('#w:rsidRDefault="........"#isx', "",$content);
            $content = preg_replace('#w:rsidRPr="........"#isx', "",$content);
            $content = preg_replace('#w:rsid.="........"#isx', "",$content);
            $content = preg_replace('#\<w:tabs\>\<w:tab w:val="clear"(.+?)\<\/w:tabs\>#isx', "",$content);
            $content = str_ireplace('<w:rPr></w:rPr>', "", $content);
            $content = str_ireplace('<w:rPr><w:lang w:val="fr-FR"/></w:rPr>', "", $content);
            $content = str_ireplace('<w:r >', '<w:r>', $content);


        /*$error_message = "Contenu nettoye = ".var_export($content, true)."\r";
        error_log($error_message, 3, "./mes-erreurs.log");*/

            // recherche de chaines à extraire :
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre"/></w:pPr>',
                "µ|NOM :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre2"/></w:pPr><w:r><w:t>',
                "|MISSION :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre2"/><w:tabs><w:tab w:val="clear" /><w:tab w:val="num" /></w:tabs><w:ind  /><w:jc w:val="left"/></w:pPr><w:r><w:t>',
                "|MISSION :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre2"/><w:tabs><w:tab w:val="clear" /></w:tabs></w:pPr><w:r><w:t>',
                "|MISSION :", $content );
            $content = str_replace( '<w:t xml:space="preserve">Objet de la </w:t></w:r><w:r ><w:t>mission</w:t>', "|OBJET :", $content );
            $content = str_replace( '<w:t>Objet de la mission</w:t>', "|OBJET :", $content );
            $content = str_replace( '<w:t xml:space="preserve">Objet de la </w:t></w:r><w:r><w:t>mission</w:t></w:r>', "|OBJET :", $content );
            $content = preg_replace( '/<w:t xml:space="preserve">Objet de la <\/w:t><\/w:r><w:r w:rsidRPr="."><w:t>mission<\/w:t><\/w:r><\/w:p><\/w:tc>/', "|OBJET :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Dates"/>',
                "µ|PERIODE :", $content );
//            $content = preg_replace( '/<w:r w:rsidRPr="."><w:t>D.tail de la mission</\w:t><\/w:r><\/w:p>/', "|DETAIL :", $content );
            $content = str_replace( '<w:t>DÃ©tail de la mission</w:t>', "|DETAIL :", $content );
            $content = str_replace( '<w:t>Environnement</w:t>', "|ENVIRONNEMENT :", $content );
            $content = str_replace( '<w:numPr><w:ilvl w:val="0"/><w:numId w:val="6"/></w:numPr>', "|COMPETENCES :", $content );
            $content = str_replace( '</w:t></w:r></w:p></w:tc></w:tr>', "/FINBLOK|", $content );
        /*$error_message = "Contenu formate = ".var_export($content, true)."\r";
        error_log($error_message, 3, "./mes-erreurs.log");*/

/*                  $replace_newlines   = preg_replace( '/<w:p w[0-9-Za-z]+:[a-zA-Z0-9]+="[a-zA-z"0-9 :="]+">/', "\n\r", $content );
                    $replace_tableRows  = preg_replace( '/<w:tr>/', "\n\r", $replace_newlines );
                    $replace_tab        = preg_replace( '/<w:tab\/>/', "\t", $replace_tableRows );
                    $replace_paragraphs = preg_replace( '/<\/w:p>/', "\n\r", $replace_tab );
                    $replace_other_Tags = strip_tags( $replace_paragraphs );
                    $content        = $replace_other_Tags;
*/
            $content         = str_replace( '</w:r></w:p></w:tc><w:tc>', " ", $content );
            $content         = str_replace( '</w:r></w:p>', "\r\n", $content );
            $striped_content = strip_tags( $content );

            return $striped_content;
        }

        public function convertToText()
        {

            if ( isset( $this->filename ) && !file_exists( $this->filename ) ) {
                return "File Not exists";
            }

            $fileArray = pathinfo( $this->filename );
            $file_ext  = $fileArray['extension'];

            if ( $file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" ||
                $file_ext == "pptx" ) {
                if ( $file_ext == "doc" ) {
                    return $this->lire_doc();
                } elseif ( $file_ext == "docx" ) {
                    return $this->lire_docx();
                } elseif ( $file_ext == "xlsx" ) {
                    return $this->xlsx_to_text();
                } elseif ( $file_ext == "pptx" ) {
                    return $this->pptx_to_text();
                }
            } else {
                return "Invalid File Type";
            }
        }

    }

function prendre_chaine_entre($chaine, $debut, $fin){
    $chaine = ' ' . $chaine;
    $ini = strpos($chaine, $debut);
    if ($ini == 0) return '';
    $ini += strlen($debut);
    $len = strpos($chaine, $fin, $ini) - $ini;
    return substr($chaine, $ini, $len);
}
  ?>