<?php
	
     /*
     ###############################################
     ####                                       ####
     ####    Author : Harish Chauhan            ####
     ####    Date   : 31 Dec,2004               ####
     ####    Updated:                           ####
     ####                                       ####
     ###############################################

     */

	 
	 /*
	 * Class is used for save the data into microsoft excel format.
	 * It takes data into array or you can write data column vise.
	 */


	Class ExcelWriter
	{
		var $file;
		var $error;
		var $newRow=false;
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		 
		function ExcelWriter($file="")
		{
			$this->file = $this->GetHeader();
		}
		
		
		function download($name)
		{	
			if($this->newRow)
			{
				$this->file .= "</tr>";
				$this->newRow=false;
			}
			
			$this->file .= $this->GetFooter();
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");;
			header("Content-Disposition: attachment;filename=$name ");
			header("Content-Transfer-Encoding: binary ");
			print $this->file;
			return ;
		}
		/* @Params : Void
		*  @return : Void
		* This function write the header of Excel file.
		*/
		 							
		function GetHeader()
		{
			$header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">

				<head>
				<meta http-equiv=Content-Type content="text/html; charset=us-ascii">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>Sriram</o:LastAuthor>
				  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
				  <o:Version>10.2625</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					white-space:normal;}
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>srirmam</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>

				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse;table-layout:fixed;'>
EOH;
			return $header;
		}

		function GetFooter()
		{
			return "</table></body></html>";
		}
		
		/*
		* @Params : $line_arr: An valid array 
		* @Return : Void
		*/
		 
		function writeLine($line_arr)
		{
			
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			$this->file.="<tr>";
			foreach($line_arr as $col)
				$this->file.="<td class=xl24 width=64 >$col</td>";
			$this->file.="</tr>";
		}

		/*
		* @Params : Void
		* @Return : Void
		*/
		function writeRow()
		{
			if($this->newRow==false)
				$this->file.="<tr>";
			else
				$this->file.="</tr><tr>";
			$this->newRow=true;	
		}

		/*
		* @Params : $value : Coloumn Value
		* @Return : Void
		*/
		function writeCol($value)
		{
			
			$this->file.="<td class=xl24 width=64 >$value</td>";
		}
	}
?>