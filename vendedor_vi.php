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
    
    
    
    class public_vendedor_vi_public_factura_venta_vi01Page extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."factura_venta_vi"');
            $field = new IntegerField('user_id');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('idventa');
            $this->dataset->AddField($field, true);
            $field = new StringField('n_factura');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('valor_total_pagar');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('abono');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('tipo_de_venta');
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('fecha_hora');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('estado');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('saldo');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('sub_total');
            $this->dataset->AddField($field, true);
            $this->dataset->AddLookupField('idventa', 'public.utilidad_factura_vendedor_vi', new IntegerField('idventa'), new IntegerField('utilidad', 'idventa_utilidad', 'idventa_utilidad_public_utilidad_factura_vendedor_vi'), 'idventa_utilidad_public_utilidad_factura_vendedor_vi');
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
                new FilterColumn($this->dataset, 'n_factura', 'n_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha Hora')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Tipo De Venta')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total ')),
                new FilterColumn($this->dataset, 'idventa', 'idventa_utilidad', $this->RenderText('Utilidad para Vendedor')),
                new FilterColumn($this->dataset, 'user_id', 'user_id', $this->RenderText('User Id'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['n_factura'])
                ->addColumn($columns['fecha_hora'])
                ->addColumn($columns['estado'])
                ->addColumn($columns['abono'])
                ->addColumn($columns['saldo'])
                ->addColumn($columns['sub_total'])
                ->addColumn($columns['valor_total_pagar']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('fecha_hora');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('n_factura_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['n_factura'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            
            $filterBuilder->addColumn(
                $columns['fecha_hora'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('estado_edit');
            
            $filterBuilder->addColumn(
                $columns['estado'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('abono_edit');
            
            $filterBuilder->addColumn(
                $columns['abono'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('saldo_edit');
            
            $filterBuilder->addColumn(
                $columns['saldo'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('sub_total_edit');
            
            $filterBuilder->addColumn(
                $columns['sub_total'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('valor_total_pagar_edit');
            
            $filterBuilder->addColumn(
                $columns['valor_total_pagar'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(false);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for utilidad field
            //
            $column = new CurrencyViewColumn('idventa', 'idventa_utilidad', 'Utilidad para Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for utilidad field
            //
            $column = new CurrencyViewColumn('idventa', 'idventa_utilidad', 'Utilidad para Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new TextEdit('n_factura_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            $editColumn = new CustomEditColumn('Fecha Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new TextEdit('estado_edit');
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for saldo field
            //
            $editor = new TextEdit('saldo_edit');
            $editColumn = new CustomEditColumn('Saldo', 'saldo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sub_total field
            //
            $editor = new TextEdit('sub_total_edit');
            $editColumn = new CustomEditColumn('Sub Total', 'sub_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total ', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new TextEdit('n_factura_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            $editColumn = new CustomEditColumn('Fecha Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new TextEdit('estado_edit');
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for saldo field
            //
            $editor = new TextEdit('saldo_edit');
            $editColumn = new CustomEditColumn('Saldo', 'saldo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sub_total field
            //
            $editor = new TextEdit('sub_total_edit');
            $editColumn = new CustomEditColumn('Sub Total', 'sub_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total ', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddPrintColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddExportColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddCompareColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
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
    
        protected function OnPrepareColumnFilter(ColumnFilter $columnFilter)
        {
        $columnFilter->setOptionsFor(
            'estado',
            array(
              
              "PENDIENTE DE PAGO"  => FilterCondition::between(0.6, 1.4),
                "CANCELADA" => FilterCondition::between(1.8, 2.5)
            
            ),
            false // no default values
        );
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        function partition_OnGetPartitions(&$partitions)
        {
            $tmp = array();
              $this->GetConnection()->ExecQueryToArray("
                SELECT   DISTINCT
            to_char(fecha_hora,'yyyy') as fecha_hora
              FROM facturar_ventas
             ORDER BY fecha_hora", $tmp
              );
             
            foreach($tmp as $anno) {
                $partitions[$anno['fecha_hora']] = $anno['fecha_hora']; 
               }
        }
        
        function partition_OnGetPartitionCondition($partitionKey, &$condition)
        {
            $condition = "to_char(fecha_hora,'yyyy') = '$partitionKey'";
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
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(true);
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
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 2:
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 1:
                   $customText=' PENDIENTE DE PAGO';
            $handled=true;
                    break;
                }
            }
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 2:
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 1:
                   $customText=' PENDIENTE DE PAGO';
            $handled=true;
                    break;
                }
            }
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 2:
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 1:
                   $customText=' PENDIENTE DE PAGO';
            $handled=true;
                    break;
                }
            }
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
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
    
    // OnBeforePageExecute event handler
    
    
    
    class public_vendedor_viPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."vendedor_vi"');
            $field = new IntegerField('user_id');
            $this->dataset->AddField($field, true);
            $field = new StringField('user_name');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
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
                new FilterColumn($this->dataset, 'user_id', 'user_id', $this->RenderText('ID Vendedor')),
                new FilterColumn($this->dataset, 'user_name', 'user_name', $this->RenderText('Nombre Vendedor'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['user_id'])
                ->addColumn($columns['user_name']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.vendedor_vi.public.factura_venta_vi01')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_vendedor_vi_public_factura_venta_vi01 detail
            //
            $column = new DetailColumn(array('user_id'), 'public.vendedor_vi.public.factura_venta_vi01', 'public_vendedor_vi_public_factura_venta_vi01_handler', $this->dataset, 'Factura Venta ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for user_id field
            //
            $column = new NumberViewColumn('user_id', 'user_id', 'ID Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_vendedor_viGrid_user_name_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for user_id field
            //
            $column = new NumberViewColumn('user_id', 'user_id', 'ID Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_vendedor_viGrid_user_name_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for user_id field
            //
            $editor = new TextEdit('user_id_edit');
            $editColumn = new CustomEditColumn('ID Vendedor', 'user_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for user_name field
            //
            $editor = new TextAreaEdit('user_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Nombre Vendedor', 'user_name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for user_id field
            //
            $editor = new TextEdit('user_id_edit');
            $editColumn = new CustomEditColumn('ID Vendedor', 'user_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for user_name field
            //
            $editor = new TextAreaEdit('user_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Nombre Vendedor', 'user_name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for user_id field
            //
            $column = new NumberViewColumn('user_id', 'user_id', 'ID Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_vendedor_viGrid_user_name_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for user_id field
            //
            $column = new NumberViewColumn('user_id', 'user_id', 'ID Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_vendedor_viGrid_user_name_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for user_id field
            //
            $column = new NumberViewColumn('user_id', 'user_id', 'ID Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_vendedor_viGrid_user_name_handler_compare');
            $grid->AddCompareColumn($column);
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
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
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
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(true);
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
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new public_vendedor_vi_public_factura_venta_vi01Page('public_vendedor_vi_public_factura_venta_vi01', $this, array('user_id'), array('user_id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.vendedor_vi.public.factura_venta_vi01'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.vendedor_vi.public.factura_venta_vi01'));
            $detailPage->SetTitle('Factura Venta ');
            $detailPage->SetMenuLabel('Factura Venta ');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_vendedor_vi_public_factura_venta_vi01_handler');
            $handler = new PageHTTPHandler('public_vendedor_vi_public_factura_venta_vi01_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_vendedor_viGrid_user_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_vendedor_viGrid_user_name_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_vendedor_viGrid_user_name_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_name', 'user_name', 'Nombre Vendedor', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_vendedor_viGrid_user_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
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
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
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
        $Page = new public_vendedor_viPage("public_vendedor_vi", "vendedor_vi.php", GetCurrentUserGrantForDataSource("public.vendedor_vi"), 'UTF-8');
        $Page->SetTitle('Vendedor ');
        $Page->SetMenuLabel('Vendedor ');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.vendedor_vi"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
