<?php
/* Script d'extraction du CV pour alimenter les missions et les compétences */
    header('Content-type: text/html; charset=utf8');
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
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre"/><w:rPr><w:lang w:val="en-US"/></w:rPr></w:pPr>',
                "µ|NOM :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Titre"/><w:rPr><w:lang w:val="en-US"/></w:rPr></w:pPr>',
                "|TITRE :", $content );
            $content = str_replace( '<w:szCs w:val="24"/></w:rPr>',
                "|MISSION :", $content );
            $content = str_replace( '<w:pPr><w:pStyle w:val="Dates"/>',
                "µ|PERIODE :", $content );
            $content =
                str_replace( '<w:t xml:space="preserve">Objet de la </w:t></w:r><w:r w:rsidRPr="008369C3"><w:t>mission</w:t></w:r></w:p></w:tc>', "|OBJET :", $content );
            $content = str_replace( '<w:r w:rsidRPr="00564F0A"><w:t>Detail de la mission</w:t></w:r></w:p>', "|DETAIL :", $content );
            $content = str_replace( '<w:t>DÃ©tail de la mission</w:t>', "|DETAIL :", $content );
            $content = str_replace( '<w:t>Environnement</w:t>', "|ENVIRONNEMENT :", $content );
            $content = str_replace( '</w:t></w:r></w:p></w:tc></w:tr>', "/FINBLOK|", $content );

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