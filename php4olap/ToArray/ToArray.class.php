<?php 

/*
* This file is part of php4olap.
*/
namespace php4olap\ToArray;
/**
*	@package ToArray
*/
class ToArray{
    
	public $resultSet;
    public $ToArray;
    public $displayRowColHierarchyTitle = false;
    
    public function __construct ($resultSet){
		$this->resultSet = $resultSet;
	}
   /**
     * 解析元素信息
     */
	public function generate(){
        $this->ToArray['header'] =  $this->generateHeader();
        $this->ToArray['body'] =  $this->generateBody();
		return $this->ToArray;	
	}
    /**
     * 获取标题
     */
	public function generateHeader(){
		$rowNb = count($this->resultSet->getColHierarchiesName());
		$colNb = count($this->resultSet->getColAxisSet());
		$header = array();
		foreach ($this->resultSet->getColHierarchiesName() as $row => $colHierarchyName) {
			$rowContent = '';
			foreach ($this->resultSet->getRowHierarchiesName() as $col => $rowHierarchyName) {
			    $topLeft = ($row == 0 && $col == 0);
				if ($row +1 == count($this->resultSet->getColHierarchiesName()) ) {				
					$rowContent[] = $rowHierarchyName;
				} else { // empty cells
					$rowContent[] = '';
				}
			}
			// Axis
			$colAxisSet = $this->resultSet->getColAxisSet();
			for ($col=0; $col < $colNb ; $col++) { 
				$rowContent[] = $this->renderHeaderCellAxis($col, $row, $colAxisSet);
			}
			$header[] = $rowContent;
		}
		return $header;
	}
    /**
     * 获取数据详情
     */
	public function generateBody(){
		$body = array();
		$rowAxisSet = $this->resultSet->getRowAxisSet();
		foreach($rowAxisSet as $row => $aCol){
			$rowContent = array();
			$even = ($row%2 == 0) ? true : false;
			// Axis cells
			foreach ($aCol as $col => $oCol) {
				$rowContent[] = $this->renderBodyCellAxis($row, $col, $rowAxisSet);
			}
			// Datas
			$rowNum = count($this->resultSet->getColAxisSet());
			$start =  $rowNum * $row;
			$stop = $start + $rowNum;
			for ($i=$start; $i < $stop; $i++) { 
				$rowContent[]= $this->renderBodyCellData($i);
			}
			$body[] = $rowContent;			
		}
		return $body;
	}
    /**
     * header Axis
     */
	protected function renderHeaderCellAxis($row, $col, $axisSet){
		if (!$this->ifDisplayAxisCell($row, $col, $axisSet)) {
			return;
		}
		return sprintf($axisSet[$row][$col]->getMemberCaption());
	}
    /**
     * Body Axis
     */
	protected function renderBodyCellAxis($row, $col, $axisSet){	
		if (!$this->ifDisplayAxisCell($row, $col, $axisSet)) {
			return;
		}
		$caption =  $axisSet[$row][$col]->getMemberCaption();
		return sprintf($caption);
	}
    /**
     * Body data
     */
	protected function renderBodyCellData($ordinal){
		$dataSet = $this->resultSet->getDataSet();
		if (isset($dataSet[$ordinal])) {
			return sprintf($dataSet[$ordinal]->getFormatedValue());
		} else {
			return '';
		}		
	}
    
    protected function ifDisplayAxisCell($row, $col, $axisSet){
		if ( $row == 0 ) {
			return true;
		} elseif ($this->countAxisMemberSize($row-1, $col, $axisSet) > 1) {
			return false;
		} else {
			return true;
		}
	}
    
    protected function countAxisMemberSize($row, $col, $axisSet){
		$size = 0;
		$stop = false;
		while ( !$stop ){
			$size++;
			if (!isset($axisSet[$row+$size][$col])){
				break;
			}
			for ($i=$col; $i >= 0 ; $i--) { 
				if ($axisSet[$row][$i]->getMemberUniqueName() != $axisSet[$row+$size][$i]->getMemberUniqueName()){
					$stop = true;
					break;
				}
			}
		}
		return $size;		
	}
	
}