<?php 

/*
* This file is part of php4olap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace php4olap\Tests\Xmla\Connection;

use php4olap\Xmla\Connection\Connection;
use php4olap\Xmla\Metadata\Database;
use php4olap\Xmla\Metadata\Catalog;
use php4olap\Xmla\Metadata\Schema;


class ConnectionTest extends \PHPUnit_Framework_TestCase
{

	public function testAdaptator()
	{
		$adaptator = $this->getMock('php4olap\Xmla\Connection\Adaptator\SoapAdaptator', array(), array(), '', FALSE);
		$connection = new Connection($adaptator);
		$this->assertEquals($adaptator, $connection->getSoapAdaptator());
	}	


	public function testFindCubes()
	{
		$adaptator = $this->getAdaptator('
				<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<CUBE_NAME>Sales Ragged</CUBE_NAME>
						<CUBE_TYPE>CUBE</CUBE_TYPE>
						<LAST_SCHEMA_UPDATE>2011-05-07T00:52:12</LAST_SCHEMA_UPDATE>
						<IS_DRILLTHROUGH_ENABLED>true</IS_DRILLTHROUGH_ENABLED>
						<IS_WRITE_ENABLED>false</IS_WRITE_ENABLED>
						<IS_LINKABLE>false</IS_LINKABLE>
						<IS_SQL_ENABLED>false</IS_SQL_ENABLED>
						<DESCRIPTION>FoodMart Schema - Sales Ragged Cube</DESCRIPTION>
					</row>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<CUBE_NAME>Store</CUBE_NAME>
						<CUBE_TYPE>CUBE</CUBE_TYPE>
						<LAST_SCHEMA_UPDATE>2011-05-07T00:52:12</LAST_SCHEMA_UPDATE>
						<IS_DRILLTHROUGH_ENABLED>true</IS_DRILLTHROUGH_ENABLED>
						<IS_WRITE_ENABLED>false</IS_WRITE_ENABLED>
						<IS_LINKABLE>false</IS_LINKABLE>
						<IS_SQL_ENABLED>false</IS_SQL_ENABLED>
						<DESCRIPTION>FoodMart Schema - Store Cube</DESCRIPTION>
					</row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$cubes = $connection->findCubes();

		$cube = current($cubes);		
		$this->assertEquals('Sales Ragged', $cube->getName());
		$cube = $cubes['[Store]'];		
		$this->assertEquals('Store', $cube->getName());

	}

	public function testFindDimensions()
	{
		$adaptator = $this->getAdaptator('
				<root>
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <CUBE_NAME>Sales</CUBE_NAME> 
			        <DIMENSION_NAME>Education Level</DIMENSION_NAME> 
			        <DIMENSION_UNIQUE_NAME>[Education Level]</DIMENSION_UNIQUE_NAME> 
			        <DIMENSION_CAPTION>Education Level</DIMENSION_CAPTION> 
			        <DIMENSION_ORDINAL>9</DIMENSION_ORDINAL> 
			        <DIMENSION_TYPE>3</DIMENSION_TYPE> 
			        <DIMENSION_CARDINALITY>6</DIMENSION_CARDINALITY> 
			        <DEFAULT_HIERARCHY>[Education Level]</DEFAULT_HIERARCHY> 
			        <DESCRIPTION>Sales Cube - Education Level Dimension</DESCRIPTION> 
			        <IS_VIRTUAL>false</IS_VIRTUAL> 
			        <IS_READWRITE>false</IS_READWRITE> 
			        <DIMENSION_UNIQUE_SETTINGS>0</DIMENSION_UNIQUE_SETTINGS> 
			        <DIMENSION_IS_VISIBLE>true</DIMENSION_IS_VISIBLE> 
			      </row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$dims = $connection->findDimensions();

		$dim = current($dims);		
		$this->assertEquals('Education Level', $dim->getName());

	}

	public function testFindHierarchies()
	{
		$adaptator = $this->getAdaptator('
				<root>
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <CUBE_NAME>Sales</CUBE_NAME> 
			        <DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME> 
			        <HIERARCHY_NAME>Time</HIERARCHY_NAME> 
			        <HIERARCHY_UNIQUE_NAME>[Time]</HIERARCHY_UNIQUE_NAME> 
			        <HIERARCHY_CAPTION>Time</HIERARCHY_CAPTION> 
			        <DIMENSION_TYPE>1</DIMENSION_TYPE> 
			        <HIERARCHY_CARDINALITY>34</HIERARCHY_CARDINALITY> 
			        <DEFAULT_MEMBER>[Time].[1997]</DEFAULT_MEMBER> 
			        <DESCRIPTION>Sales Cube - Time Hierarchy</DESCRIPTION> 
			        <STRUCTURE>0</STRUCTURE> 
			        <IS_VIRTUAL>false</IS_VIRTUAL> 
			        <IS_READWRITE>false</IS_READWRITE> 
			        <DIMENSION_UNIQUE_SETTINGS>0</DIMENSION_UNIQUE_SETTINGS> 
			        <DIMENSION_IS_VISIBLE>true</DIMENSION_IS_VISIBLE> 
			        <HIERARCHY_ORDINAL>4</HIERARCHY_ORDINAL> 
			        <DIMENSION_IS_SHARED>true</DIMENSION_IS_SHARED> 
			        <PARENT_CHILD>false</PARENT_CHILD> 
			      </row> 
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <CUBE_NAME>Sales</CUBE_NAME> 
			        <DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME> 
			        <HIERARCHY_NAME>Time.Weekly</HIERARCHY_NAME> 
			        <HIERARCHY_UNIQUE_NAME>[Time.Weekly]</HIERARCHY_UNIQUE_NAME> 
			        <HIERARCHY_CAPTION>Weekly</HIERARCHY_CAPTION> 
			        <DIMENSION_TYPE>1</DIMENSION_TYPE> 
			        <HIERARCHY_CARDINALITY>837</HIERARCHY_CARDINALITY> 
			        <DEFAULT_MEMBER>[Time.Weekly].[All Time.Weeklys]</DEFAULT_MEMBER> 
			        <ALL_MEMBER>[Time.Weekly].[All Time.Weeklys]</ALL_MEMBER> 
			        <DESCRIPTION>Sales Cube - Time.Weekly Hierarchy</DESCRIPTION> 
			        <STRUCTURE>0</STRUCTURE> 
			        <IS_VIRTUAL>false</IS_VIRTUAL> 
			        <IS_READWRITE>false</IS_READWRITE> 
			        <DIMENSION_UNIQUE_SETTINGS>0</DIMENSION_UNIQUE_SETTINGS> 
			        <DIMENSION_IS_VISIBLE>true</DIMENSION_IS_VISIBLE> 
			        <HIERARCHY_ORDINAL>5</HIERARCHY_ORDINAL> 
			        <DIMENSION_IS_SHARED>true</DIMENSION_IS_SHARED> 
			        <PARENT_CHILD>false</PARENT_CHILD> 
			      </row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$dims = $connection->findHierarchies();

		$dim = current($dims);		
		$this->assertEquals('Time', $dim->getName());
		$this->assertEquals(4, $dim->getOrdinal());
	}

	public function testFindLevels()
	{
		$adaptator = $this->getAdaptator('
				<root>
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <CUBE_NAME>Sales</CUBE_NAME> 
			        <DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME> 
			        <HIERARCHY_UNIQUE_NAME>[Time.Weekly]</HIERARCHY_UNIQUE_NAME> 
			        <LEVEL_NAME>(All)</LEVEL_NAME> 
			        <LEVEL_UNIQUE_NAME>[Time.Weekly].[(All)]</LEVEL_UNIQUE_NAME> 
			        <LEVEL_CAPTION>(All)</LEVEL_CAPTION> 
			        <LEVEL_NUMBER>0</LEVEL_NUMBER> 
			        <LEVEL_CARDINALITY>1</LEVEL_CARDINALITY> 
			        <LEVEL_TYPE>1</LEVEL_TYPE> 
			        <CUSTOM_ROLLUP_SETTINGS>0</CUSTOM_ROLLUP_SETTINGS> 
			        <LEVEL_UNIQUE_SETTINGS>3</LEVEL_UNIQUE_SETTINGS> 
			        <LEVEL_IS_VISIBLE>true</LEVEL_IS_VISIBLE> 
			        <DESCRIPTION>Sales Cube - Time.Weekly Hierarchy - (All) Level</DESCRIPTION> 
			      </row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$level = $connection->findLevels();

		$level = current($level);		
		$this->assertEquals('(All)', $level->getName());
		$this->assertEquals('[Time.Weekly].[(All)]', $level->getUniqueName());
	}
	
	public function testFindMeasures()
	{
		$adaptator = $this->getAdaptator('
				<root>
			      <row>
			        <CATALOG_NAME>FoodMart</CATALOG_NAME>
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
			        <CUBE_NAME>Sales</CUBE_NAME>
			        <MEASURE_NAME>Profit Growth</MEASURE_NAME>
			        <MEASURE_UNIQUE_NAME>[Measures].[Profit Growth]</MEASURE_UNIQUE_NAME>
			        <MEASURE_CAPTION>Gewinn-Wachstum</MEASURE_CAPTION>
			        <MEASURE_AGGREGATOR>127</MEASURE_AGGREGATOR>
			        <DATA_TYPE>130</DATA_TYPE>
			        <MEASURE_IS_VISIBLE>true</MEASURE_IS_VISIBLE>
			        <DESCRIPTION>Sales Cube - Profit Growth Member</DESCRIPTION>
			      </row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$measures = $connection->findMeasures();

		$measure = current($measures);		
		$this->assertEquals('Profit Growth', $measure->getName());
	}

	public function testFindMembers()
	{
		$adaptator = $this->getAdaptator('
				<root>
			      <row>
			        <CATALOG_NAME>FoodMart</CATALOG_NAME>
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
			        <CUBE_NAME>Sales</CUBE_NAME>
			        <DIMENSION_UNIQUE_NAME>[Measures]</DIMENSION_UNIQUE_NAME>
			        <HIERARCHY_UNIQUE_NAME>[Measures]</HIERARCHY_UNIQUE_NAME>
			        <LEVEL_UNIQUE_NAME>[Measures].[MeasuresLevel]</LEVEL_UNIQUE_NAME>
			        <MEMBER_NAME>Store Cost</MEMBER_NAME>
			        <MEMBER_UNIQUE_NAME>[Measures].[Store Cost]</MEMBER_UNIQUE_NAME>
			      </row>
				</root>');
		
		$connection = new Connection($adaptator);
		
		$measures = $connection->findMembers();

		$measure = current($measures);		
		$this->assertEquals('Store Cost', $measure->getName());
	}

	public function testStatement()
	{
		$statementResult = new \DOMDocument();
		$statementResult->load(__DIR__.'/statementResult.xml');
		$adaptator = $this->getStatementAdaptator($statementResult->saveXml());
		
		$connection = new Connection($adaptator);
		
		$resultSet = $connection->statement("SELECT {[Measures].[Org Salary]} ON columns, Hierarchize(Union({[Employees].[All Employees]}, [Employees].[All Employees].Children)) ON rows FROM HR WHERE ([Time].[1997])");
		
		$this->assertEquals('$39,431.67', $resultSet->getDataCell(1)->getFormatedValue());
	}

	public function testSetDefault()
	{
		
		$array = array();
		$array = Connection::setDefault('Format', 'Tabular', $array);
		$result = array('Format' => 'Tabular');
		$this->assertEquals($array, $result);

		$array = null;
		$array = Connection::setDefault('Format', 'Tabular', $array);
		$result = array('Format' => 'Tabular');
		$this->assertEquals($array, $result);

		$array = array('MyParam' => 'MyValue');
		$array = Connection::setDefault('Format', 'Tabular', $array);
		$result = array('Format' => 'Tabular', 'MyParam' => 'MyValue');
		$this->assertEquals($array, $result);

		$array = array('Format' => 'myFormat');
		$array = Connection::setDefault('Format', 'Tabular', $array);
		$result = array('Format' => 'myFormat');
		$this->assertEquals($array, $result);

		$array = array('Format' => 'Tabular');
		$array = Connection::setDefault('Format', null, $array);
		$result = array('Format' => 'Tabular');
		$this->assertEquals($array, $result);

		$array = array();
		$array = Connection::setDefault('Format', null, $array);
		$result = array();
		$this->assertEquals($array, $result);

	}
	
	public function getAdaptator($Xml)
	{
		$document = new \DOMDocument();
		$document->loadXML($Xml);
		$node = $document->getElementsByTagName('row');
   		
		$adaptator = $this->getMock(
				'php4olap\Xmla\Connection\Adaptator\SoapAdaptator',
				array('call'),
				array('http://localhost:8080/mondrian/xmla.jsp')
				); 
        $adaptator->expects($this->any()) 
             ->method('call') 
             ->will($this->returnValue($node)); 
		return $adaptator;		
	}
	public function getStatementAdaptator($Xml)
	{
		$document = new \DOMDocument();
		$document->loadXML($Xml);
		$node = $document->getElementsByTagName('root')->item(0);
   		
		$adaptator = $this->getMock(
				'php4olap\Xmla\Connection\Adaptator\SoapAdaptator',
				array('call'),
				array('http://localhost:8080/mondrian/xmla.jsp')
				); 
        $adaptator->expects($this->any()) 
             ->method('call') 
             ->will($this->returnValue($node)); 
		return $adaptator;		
	}
	
}