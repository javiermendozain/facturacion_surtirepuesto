<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/pgsql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class public_deudas_memoPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."deudas_memo"');
            $field = new IntegerField('id_deuda', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nit_ccc');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('valor');
            $this->dataset->AddField($field, false);
            $field = new StringField('descricion');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idventa');
            $this->dataset->AddField($field, false);
            $field = new DateField('fecha');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('idventa', 'public.factura_abono', new IntegerField('idventa'), new StringField('factura_saldo', 'idventa_factura_saldo', 'idventa_factura_saldo_public_factura_abono'), 'idventa_factura_saldo_public_factura_abono');
            $this->dataset->AddLookupField('nit_ccc', 'public.clientes', new StringField('nit_cc'), new StringField('nombre_completo', 'nit_ccc_nombre_completo', 'nit_ccc_nombre_completo_public_clientes'), 'nit_ccc_nombre_completo_public_clientes');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new CustomPageNavigator('partition', $this, $this->GetDataset(), $this->RenderText('Año'), $result, 'partition');
            $partitionNavigator->OnGetPartitions->AddListener('partition_OnGetPartitions', $this);
            $partitionNavigator->OnGetPartitionCondition->AddListener('partition_OnGetPartitionCondition', $this);
            $partitionNavigator->SetAllowViewAllRecords(false);
            $partitionNavigator->SetNavigationStyle(NS_LIST);
            $result->AddPageNavigator($partitionNavigator);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'fecha', 'fecha', $this->RenderText('Fecha')),
                new FilterColumn($this->dataset, 'idventa', 'idventa_factura_saldo', $this->RenderText('')),
                new FilterColumn($this->dataset, 'id_deuda', 'id_deuda', $this->RenderText('Id Deuda')),
                new FilterColumn($this->dataset, 'nit_ccc', 'nit_ccc_nombre_completo', $this->RenderText('Cliente')),
                new FilterColumn($this->dataset, 'valor', 'valor', $this->RenderText('Valor')),
                new FilterColumn($this->dataset, 'descricion', 'descricion', $this->RenderText('Observaci&oacute;n'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['fecha'])
                ->addColumn($columns['idventa'])
                ->addColumn($columns['descricion']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('fecha')
                ->setOptionsFor('idventa');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for fecha field
            //
            $column = new DateTimeViewColumn('fecha', 'fecha', 'Fecha', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for factura_saldo field
            //
            $column = new TextViewColumn('idventa', 'idventa_factura_saldo', '', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('# Factura '));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor field
            //
            $column = new CurrencyViewColumn('valor', 'valor', 'Valor', $this->dataset);
            $column->setNullLabel($this->RenderText('-'));
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descricion field
            //
            $column = new TextViewColumn('descricion', 'descricion', 'Observaci&oacute;n', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for fecha field
            //
            $column = new DateTimeViewColumn('fecha', 'fecha', 'Fecha', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for factura_saldo field
            //
            $column = new TextViewColumn('idventa', 'idventa_factura_saldo', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor field
            //
            $column = new CurrencyViewColumn('valor', 'valor', 'Valor', $this->dataset);
            $column->setNullLabel($this->RenderText('-'));
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descricion field
            //
            $column = new TextViewColumn('descricion', 'descricion', 'Observaci&oacute;n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for fecha field
            //
            $editor = new DateTimeEdit('fecha_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Fecha', 'fecha', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idventa field
            //
            $editor = new MultiLevelComboBoxEditor('idventa_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $editor->setNumberOfValuesToDisplay(20);
            
            $dataset0 = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."cliente_abono"');
            $field = new StringField('nit_cc');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'nit_cc', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'nit_cc', 'nombre', $this->RenderText('Cliente Factura'), null);
            
            $dataset1 = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."factura_abono"');
            $field = new StringField('nit_cc');
            $dataset1->AddField($field, false);
            $field = new IntegerField('idventa');
            $dataset1->AddField($field, true);
            $field = new StringField('factura_saldo');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('factura_saldo', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idventa', 'factura_saldo', new ForeignKeyInfo('nit_cc', 'nit_cc'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idventa', 'factura_saldo', $this->RenderText(''), new ForeignKeyInfo('nit_cc', 'nit_cc'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idventa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor field
            //
            $editor = new TextEdit('valor_edit');
            $editColumn = new CustomEditColumn('Valor', 'valor', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descricion field
            //
            $editor = new TextEdit('descricion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Observaci&oacute;n', 'descricion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for fecha field
            //
            $editor = new DateTimeEdit('fecha_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Fecha', 'fecha', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idventa field
            //
            $editor = new MultiLevelComboBoxEditor('idventa_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $editor->setNumberOfValuesToDisplay(20);
            
            $dataset0 = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."cliente_abono"');
            $field = new StringField('nit_cc');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'nit_cc', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'nit_cc', 'nombre', $this->RenderText('Cliente Factura'), null);
            
            $dataset1 = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."factura_abono"');
            $field = new StringField('nit_cc');
            $dataset1->AddField($field, false);
            $field = new IntegerField('idventa');
            $dataset1->AddField($field, true);
            $field = new StringField('factura_saldo');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('factura_saldo', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idventa', 'factura_saldo', new ForeignKeyInfo('nit_cc', 'nit_cc'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idventa', 'factura_saldo', $this->RenderText(''), new ForeignKeyInfo('nit_cc', 'nit_cc'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idventa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for valor field
            //
            $editor = new TextEdit('valor_edit');
            $editColumn = new CustomEditColumn('Valor', 'valor', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descricion field
            //
            $editor = new TextEdit('descricion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Observaci&oacute;n', 'descricion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for fecha field
            //
            $column = new DateTimeViewColumn('fecha', 'fecha', 'Fecha', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for factura_saldo field
            //
            $column = new TextViewColumn('idventa', 'idventa_factura_saldo', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor field
            //
            $column = new CurrencyViewColumn('valor', 'valor', 'Valor', $this->dataset);
            $column->setNullLabel($this->RenderText('-'));
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descricion field
            //
            $column = new TextViewColumn('descricion', 'descricion', 'Observaci&oacute;n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for fecha field
            //
            $column = new DateTimeViewColumn('fecha', 'fecha', 'Fecha', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for factura_saldo field
            //
            $column = new TextViewColumn('idventa', 'idventa_factura_saldo', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for valor field
            //
            $column = new CurrencyViewColumn('valor', 'valor', 'Valor', $this->dataset);
            $column->setNullLabel($this->RenderText('-'));
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
            
            //
            // View column for descricion field
            //
            $column = new TextViewColumn('descricion', 'descricion', 'Observaci&oacute;n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
    
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        
        public function GetEnableModalGridInsert() { return true; }
        function partition_OnGetPartitions(&$partitions)
        {
            $tmp = array();
              $this->GetConnection()->ExecQueryToArray("
                SELECT   DISTINCT
            to_char(fecha,'yyyy') as fecha
              FROM deudas_memo
             ORDER BY fecha", $tmp
              );
             
            foreach($tmp as $anno) {
                $partitions[$anno['fecha']] = $anno['fecha']; 
               }
        }
        
        function partition_OnGetPartitionCondition($partitionKey, &$condition)
        {
            $condition = "to_char(fecha,'yyyy') = '$partitionKey'";
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            $result->SetTotal('valor', PredefinedAggregate::$Sum);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setExportListAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientEditorValueChangedScript($this->RenderText('if (sender.getFieldName() === \'nit_ccc\') {
                            var country = sender.getData();
                            editors.idventa
                                .setData(null)
                                .setEnabled(country);
                        
                            if (country) {
                                editors.idventa.setQueryFunction(function (term) {
                                    return {
                                        term: term,
                                        fields: {
                                            nit_ccc: deudas_memo.fields.nit_ccc
                                        }
                                    };
                                });
                            }
                        }
                        
                        
                        if (sender.getFieldName() === \'country_code\') {
                var country = sender.getData();
                editors.city_id
                    .setData(null)
                    .setEnabled(country);
            
                if (country) {
                    editors.city_id.setQueryFunction(function (term) {
                        return {
                            term: term,
                            fields: {
                                CountryCode: country.fields.Code
                            }
                        };
                    });
                }
            }'));
        }
    
        protected function doRegisterHandlers() {
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
            if ($columnName=='valor')
            {
             $customText = '<p ALIGN=righ  ><strong >Total: $ ' . $totalValue . '</strong></p>';
             $handled = true;   
            }
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['descricion']=$rowData['descricion'].'. $'.$rowData['valor'].'-'.$this->GetEnvVar('CURRENT_USER_NAME');  
            
            
            
            include('conexion.php');
            
            $sql='SELECT 
              facturar_ventas.valor_total_pagar, facturar_ventas.saldo
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].';';
            $rs=pg_query($conn,$sql);
              while ($row=pg_fetch_row($rs)) { 
              if($rowData['valor']<=$row[0]){
              
             
                     if($row[1]-$rowData['valor']==0){
            
                $this->GetConnection()->ExecSQL("UPDATE facturar_ventas
               SET   abono=abono+".$rowData['valor'].", 
                   saldo=valor_total_pagar-abono-".$rowData['valor'].",  estado=2
             WHERE idventa =".$rowData['idventa']."");
                                                       }else{
                  $this->GetConnection()->ExecSQL("UPDATE facturar_ventas
               SET   abono=abono+".$rowData['valor'].", 
                   saldo=".$row[0]-$rowData['valor']."
             WHERE idventa =".$rowData['idventa']."");
             
                                                           }
              }else{
              $cancel=true;
              $message='EL ABONO DEBE SER MENOR O IGUAL QUE EL VALOR TOTAL A PAGAR';
              $messageDisplayTime=15;
              }
              }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new public_deudas_memoPage("public_deudas_memo", "deudas_memo.php", GetCurrentUserGrantForDataSource("public.deudas_memo"), 'UTF-8');
        $Page->SetTitle('Abono Cliente');
        $Page->SetMenuLabel('Abono');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.deudas_memo"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
