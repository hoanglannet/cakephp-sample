<?php
class ImportsComponent extends Object {

	//Excel
	public function importExcel($spaceArr, $dirSave = '', $isOpen = true) {
		App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
		    		
    	$objReader      =   PHPExcel_IOFactory::createReader('Excel5');
    	Configure::load('appconfig');
		$webroot = Configure::read('App.CONTEXT_PATH');
		$objPHPExcel    =   $objReader->load(realpath('files/template/share_properties.xls'));
		$nameSheet = 'share_properties';
		$objWorksheet   =   $objPHPExcel->getSheetByName($nameSheet);
		if ($objWorksheet != null) {
			Utils::useModels($this, array('Space', 'City', 'SpaceNote', 'Media'));
	
			$this->Space->recursive = 2;
			$this->Space->bindModel($this->Space->binds['belongsTo']);	
			$this->Space->bindModel($this->Space->binds['hasMany']);
			$this->City->bindModel($this->City->binds['belongsTo']);
			$this->SpaceNote->bindModel($this->SpaceNote->binds['belongsTo']);								
			$spaces = $this->Space->find('all', array('conditions' => array('Space.id' => $spaceArr)));		
			
			$imageNeedDelete = array();
            $this->setDataIntoSheet($spaces, &$objWorksheet, &$imageNeedDelete);
		}
		
		$date = date('m_d_Y_H_s_i');
		$fileName = 'share_properties_'.$date.'.xls';
		$objWriter  =   PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		
        if ($isOpen) {
        	header('Content-Type: application/vnd.ms-excel');
        	header('Content-Disposition: attachment;filename='.$fileName);
        	header('Cache-Control: max-age=0');
        	$objWriter->save('php://output');
        } else {
        	$objWriter->save($dirSave.$fileName);
        }
	    //Deleting image inserted in Excel
		foreach ($imageNeedDelete as $item) {
			@unlink(realpath($item));
		}
		if (!$isOpen) {
        	return $dirSave.$fileName;
		}
		
	} 
	
	private function setDataIntoSheet($spaces, &$objWorksheet, &$imageNeedDelete = array()) {
		$alignCenter = array(
            'alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
		);
		$first = 6;
		$i = 1;
		App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/PHPExcel.php'));
		Configure::load('appconfig');	
		$typeSpace = Configure::read('TypeSpace');
		$typeLease = Configure::read('TypeLease');
		$objWorksheet->setCellValue('E3', date('m/d/Y'));
		
		
		foreach ($spaces as $value) {
			//fill alignment
			$row = ($first+$i);
			$objWorksheet->setCellValue('A'.$row, $i);
			$objWorksheet->setCellValue('B'.$row, $value['Space']['address'].', '.$value['City']['name'].', '.$value['City']['State']['name']);
			$objWorksheet->setCellValue('C'.$row, $value['Space']['zip_code']);
			$objWorksheet->setCellValue('D'.$row, $value['Space']['floor']);
			$objWorksheet->setCellValue('E'.$row, $value['Space']['square_footage']);
			$objWorksheet->setCellValue('F'.$row, $value['Space']['availability']);
			$objWorksheet->setCellValue('G'.$row, $typeSpace[$value['Space']['space']]);
			$objWorksheet->setCellValue('H'.$row, $value['Space']['price_monthly']);
			$objWorksheet->setCellValue('I'.$row, $value['Space']['price_year']);
			$totalYear = 0;
			$totalMonth = 0;
			if (!empty($value['Space']['price_monthly'])) {
				$totalMonth = $value['Space']['price_monthly']*$value['Space']['square_footage'];
			}
			if (!empty($value['Space']['price_year'])) {
				$totalYear = ($value['Space']['price_year']/12)*$value['Space']['square_footage'];
			} 
			$objWorksheet->setCellValue('J'.$row, round($totalMonth, 2));
			$objWorksheet->setCellValue('K'.$row, round($totalYear, 2));
			$objWorksheet->setCellValue('L'.$row, $typeLease[$value['Space']['type_lease']]);
			$objWorksheet->setCellValue('M'.$row, $value['Space']['rating']);
			$objWorksheet->setCellValue('N'.$row, $value['Space']['general_space_note']);
			
			$listNote = $value['SpaceNote'];
			$str = '';	
			foreach ($listNote as $item) {
				$str .= $item['SpaceNoteTemplate']['question'];
				if (isset($item['answer']) &&($item['answer'] == 1)) {
					$str .= " (True)\r\n";
				} else {
					$str .= " (False)\r\n";
				}
			}
			$objWorksheet->setCellValue('O'.$row, $str);
			$objWorksheet->getStyle('O'.$row)->getAlignment()->setWrapText(true);
			
			
			//Image
			if (!empty($value['Media'])) {
				$strVideo = '';
				$isImage = false;
				$col = 'Q'; 				
				foreach ( $value['Media'] as $item) {
					if ($item['type'] == MEDIA_IMAGE) {
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$linkThumb = substr_replace($item['link'], '_thumb', strrpos($item['link'], '.'), 0); 
						if (Utils::smart_resize_image( $item['link'], $linkThumb, EXCEL_WIDTH_IMAGE, EXCEL_HEIGHT_IMAGE, false, 'file', false)) {
							$objDrawing->setPath($linkThumb);
							$objDrawing->setCoordinates($col.$row);
							$objDrawing->setHeight(EXCEL_HEIGHT_IMAGE);
							$objDrawing->setWidth(EXCEL_WIDTH_IMAGE);
							$objWorksheet->getColumnDimension($col)->setWidth(EXCEL_WIDTH_IMAGE/6);
							$objDrawing->setWorksheet($objWorksheet);
							array_push($imageNeedDelete, $linkThumb);
							$col = Utils::increase($col);
							$isImage = true;
						}
					} else {
						$strVideo .= $item['link']."\r\n";
					}
				}
				
				if (!empty($strVideo)) {
					$objWorksheet->setCellValue('P'.$row, $strVideo);
				}
				
				if ($isImage) {
					$objWorksheet->getRowDimension($row)->setRowHeight(EXCEL_HEIGHT_IMAGE-30);
					$objWorksheet->duplicateStyleArray($alignCenter, 'A'.$row.':P'.$row);
				}
			}
			$i++;
		}
		
		
		$this->setDefaultStyle($objWorksheet, $first, $first+$i-1);
	}

	private function setDefaultStyle(&$objWorksheet, $first, $end){
		$borderB = array(
            'borders' => array(
                'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			)
		);
		$borderR = array(
            'borders' => array(
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
		);
		$borderL = array(
            'borders' => array(
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
		);
		$alignCenter = array(
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		);
		$cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P');
		 
		foreach ($cols as $item) {
			$objWorksheet->duplicateStyleArray($borderL, $item.$first.':'.$item.$end);
		}
		$objWorksheet->duplicateStyleArray($borderR, $item.$first.':'.$item.$end);
		$objWorksheet->duplicateStyleArray($borderB, 'A'.$end.':P'.$end);
		$objWorksheet->duplicateStyleArray($alignCenter, 'A'.$first.':A'.$end);
		
	}
	
	
}

?>