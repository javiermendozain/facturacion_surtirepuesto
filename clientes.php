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
    
    
    
    class public_clientes_public_facturar_ventas_public_factura_detalle_vi01Page extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."factura_detalle_vi"');
            $field = new IntegerField('idventa');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('descuento');
            $this->dataset->AddField($field, true);
            $field = new StringField('idproducto');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('cantidad');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('precio_venta_unitario');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('valor_parcial');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('iva');
            $this->dataset->AddField($field, true);
            $this->dataset->AddLookupField('idproducto', 'public.productos', new StringField('idproducto'), new StringField('nombre', 'idproducto_nombre', 'idproducto_nombre_public_productos'), 'idproducto_nombre_public_productos');
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
                new FilterColumn($this->dataset, 'idventa', 'idventa', $this->RenderText('Idventa')),
                new FilterColumn($this->dataset, 'idproducto', 'idproducto_nombre', $this->RenderText('Producto')),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', $this->RenderText('Cantidad')),
                new FilterColumn($this->dataset, 'descuento', 'descuento', $this->RenderText('Descuento')),
                new FilterColumn($this->dataset, 'iva', 'iva', $this->RenderText('Iva')),
                new FilterColumn($this->dataset, 'precio_venta_unitario', 'precio_venta_unitario', $this->RenderText('Precio Venta ')),
                new FilterColumn($this->dataset, 'valor_parcial', 'valor_parcial', $this->RenderText('Valor Parcial'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['cantidad'])
                ->addColumn($columns['descuento'])
                ->addColumn($columns['iva'])
                ->addColumn($columns['precio_venta_unitario'])
                ->addColumn($columns['valor_parcial']);
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
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(false);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento', $this->dataset);
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
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
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
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new StringField('idproducto');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $field = new StringField('img');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_para_vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('porcentaje_utilidad_vendedor');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Producto', 'idproducto', 'idproducto_nombre', 'edit_idproducto_nombre_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'nombre', '');
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iva field
            //
            $editor = new TextEdit('iva_edit');
            $editColumn = new CustomEditColumn('Iva', 'iva', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Venta ', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor_parcial field
            //
            $editor = new TextEdit('valor_parcial_edit');
            $editColumn = new CustomEditColumn('Valor Parcial', 'valor_parcial', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new StringField('idproducto');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $field = new StringField('img');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_para_vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('porcentaje_utilidad_vendedor');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Producto', 'idproducto', 'idproducto_nombre', 'insert_idproducto_nombre_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'nombre', '');
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for iva field
            //
            $editor = new TextEdit('iva_edit');
            $editColumn = new CustomEditColumn('Iva', 'iva', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Venta ', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for valor_parcial field
            //
            $editor = new TextEdit('valor_parcial_edit');
            $editColumn = new CustomEditColumn('Valor Parcial', 'valor_parcial', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
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
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
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
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta ', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
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
            $this->setExportListAvailable(array('excel'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new StringField('idproducto');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $field = new StringField('img');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_para_vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('porcentaje_utilidad_vendedor');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idproducto_nombre_search', 'idproducto', 'nombre', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new StringField('idproducto');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $field = new StringField('img');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_para_vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('porcentaje_utilidad_vendedor');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idproducto_nombre_search', 'idproducto', 'nombre', null, 20);
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
    
    // OnBeforePageExecute event handler
    
    
    
    class public_clientes_public_facturar_ventasPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."facturar_ventas"');
            $field = new StringField('clientes_nit_cc');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idventa', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('fecha_hora');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('tipo_de_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('abono');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('valor_total_pagar');
            $this->dataset->AddField($field, false);
            $field = new StringField('n_factura');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iva_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('descuento_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('saldo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sub_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('estado');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('printt');
            $this->dataset->AddField($field, false);
            $field = new StringField('vendedor');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('forma_pago');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('user_id', 'public.phpgen_users', new IntegerField('user_id'), new StringField('user_name', 'user_id_user_name', 'user_id_user_name_public_phpgen_users'), 'user_id_user_name_public_phpgen_users');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new CustomPageNavigator('partition', $this, $this->GetDataset(), $this->RenderText('A�o'), $result, 'partition');
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
                new FilterColumn($this->dataset, 'n_factura', 'n_factura', $this->RenderText('N� Factura')),
                new FilterColumn($this->dataset, 'clientes_nit_cc', 'clientes_nit_cc', $this->RenderText('Cliente')),
                new FilterColumn($this->dataset, 'idventa', 'idventa', $this->RenderText('Idventa')),
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha y  Hora')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado de Venta')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Estado De Venta')),
                new FilterColumn($this->dataset, 'iva_total', 'iva_total', $this->RenderText('Iva Total')),
                new FilterColumn($this->dataset, 'descuento_total', 'descuento_total', $this->RenderText('Descuento Total')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total Pagar')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_name', $this->RenderText('User Id')),
                new FilterColumn($this->dataset, 'printt', 'printt', $this->RenderText('Printt')),
                new FilterColumn($this->dataset, 'utilidad_en_venta', 'utilidad_en_venta', $this->RenderText('Utilidad En Venta')),
                new FilterColumn($this->dataset, 'vendedor', 'vendedor', $this->RenderText('Vendedor')),
                new FilterColumn($this->dataset, 'forma_pago', 'forma_pago', $this->RenderText('Forma Pago'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['n_factura'])
                ->addColumn($columns['clientes_nit_cc'])
                ->addColumn($columns['fecha_hora'])
                ->addColumn($columns['estado'])
                ->addColumn($columns['iva_total'])
                ->addColumn($columns['descuento_total'])
                ->addColumn($columns['abono'])
                ->addColumn($columns['saldo'])
                ->addColumn($columns['valor_total_pagar'])
                ->addColumn($columns['forma_pago']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('fecha_hora')
                ->setOptionsFor('estado');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('n_factura_edit');
            
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
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('clientes_nit_cc_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('clientes_nit_cc');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('clientes_nit_cc');
            
            $filterBuilder->addColumn(
                $columns['clientes_nit_cc'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
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
            
            $main_editor = new TextEdit('iva_total_edit');
            
            $filterBuilder->addColumn(
                $columns['iva_total'],
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
            
            $main_editor = new TextEdit('descuento_total_edit');
            
            $filterBuilder->addColumn(
                $columns['descuento_total'],
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
            
            $main_editor = new TextEdit('forma_pago_edit');
            
            $filterBuilder->addColumn(
                $columns['forma_pago'],
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
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas.public.factura_detalle_vi01')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_clientes_public_facturar_ventas_public_factura_detalle_vi01 detail
            //
            $column = new DetailColumn(array('idventa'), 'public.clientes.public.facturar_ventas.public.factura_detalle_vi01', 'public_clientes_public_facturar_ventas_public_factura_detalle_vi01_handler', $this->dataset, 'Detalle Factura');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N� Factura', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado de Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
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
            // View column for forma_pago field
            //
            $column = new NumberViewColumn('forma_pago', 'forma_pago', 'Forma Pago', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
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
            $column = new TextViewColumn('n_factura', 'n_factura', 'N� Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado de Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for forma_pago field
            //
            $column = new NumberViewColumn('forma_pago', 'forma_pago', 'Forma Pago', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new TextEdit('n_factura_edit');
            $editColumn = new CustomEditColumn('N� Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new ComboBox('clientes_nit_cc_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editColumn = new CustomEditColumn('Cliente', 'clientes_nit_cc', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            $editColumn = new CustomEditColumn('Fecha y  Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new TextEdit('estado_edit');
            $editColumn = new CustomEditColumn('Estado de Venta', 'estado', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iva_total field
            //
            $editor = new TextEdit('iva_total_edit');
            $editColumn = new CustomEditColumn('Iva Total', 'iva_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento_total field
            //
            $editor = new TextEdit('descuento_total_edit');
            $editColumn = new CustomEditColumn('Descuento Total', 'descuento_total', $editor, $this->dataset);
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
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total Pagar', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for forma_pago field
            //
            $editor = new TextEdit('forma_pago_edit');
            $editColumn = new CustomEditColumn('Forma Pago', 'forma_pago', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('N� Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new ComboBox('clientes_nit_cc_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editColumn = new CustomEditColumn('Cliente', 'clientes_nit_cc', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            $editColumn = new CustomEditColumn('Fecha y  Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new TextEdit('estado_edit');
            $editColumn = new CustomEditColumn('Estado de Venta', 'estado', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for iva_total field
            //
            $editor = new TextEdit('iva_total_edit');
            $editColumn = new CustomEditColumn('Iva Total', 'iva_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descuento_total field
            //
            $editor = new TextEdit('descuento_total_edit');
            $editColumn = new CustomEditColumn('Descuento Total', 'descuento_total', $editor, $this->dataset);
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
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total Pagar', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for forma_pago field
            //
            $editor = new TextEdit('forma_pago_edit');
            $editColumn = new CustomEditColumn('Forma Pago', 'forma_pago', $editor, $this->dataset);
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
            $column = new TextViewColumn('n_factura', 'n_factura', 'N� Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for clientes_nit_cc field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado de Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
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
            // View column for forma_pago field
            //
            $column = new NumberViewColumn('forma_pago', 'forma_pago', 'Forma Pago', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N� Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for clientes_nit_cc field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado de Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
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
            // View column for forma_pago field
            //
            $column = new NumberViewColumn('forma_pago', 'forma_pago', 'Forma Pago', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N� Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for clientes_nit_cc field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for idventa field
            //
            $column = new TextViewColumn('idventa', 'idventa', 'Idventa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i:s a');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado de Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('right');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new NumberViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridpublic.clientes.public.facturar_ventas_user_id_user_name_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for printt field
            //
            $column = new NumberViewColumn('printt', 'printt', 'Printt', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new NumberViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for forma_pago field
            //
            $column = new NumberViewColumn('forma_pago', 'forma_pago', 'Forma Pago', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
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
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
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
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
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
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new public_clientes_public_facturar_ventas_public_factura_detalle_vi01Page('public_clientes_public_facturar_ventas_public_factura_detalle_vi01', $this, array('idventa'), array('idventa'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas.public.factura_detalle_vi01'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.clientes.public.facturar_ventas.public.factura_detalle_vi01'));
            $detailPage->SetTitle('Detalle Factura');
            $detailPage->SetMenuLabel('Detalle Factura');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_clientes_public_facturar_ventas_public_factura_detalle_vi01_handler');
            $handler = new PageHTTPHandler('public_clientes_public_facturar_ventas_public_factura_detalle_vi01_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridpublic.clientes.public.facturar_ventas_user_id_user_name_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
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
    
    class public_clientes_idzonaNestedPage extends NestedFormPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('descripcion');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for descripcion field
            //
            $editor = new TextEdit('descripcion_edit');
            $editColumn = new CustomEditColumn('Descripci&oacute;n', 'descripcion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
            $column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
       static public function getNestedInsertHandlerName()
        {
            return get_class() . '_form_insert';
        }
    
        public function GetGridInsertHandler()
        {
            return self::getNestedInsertHandlerName();
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['saldo_zona']=$rowData['idzona'];
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class public_clientesPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $this->dataset->AddField($field, false);
            $field = new StringField('direccion');
            $this->dataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $this->dataset->AddField($field, false);
            $field = new StringField('email');
            $this->dataset->AddField($field, false);
            $field = new StringField('fax');
            $this->dataset->AddField($field, false);
            $field = new StringField('observacion');
            $this->dataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $this->dataset->AddField($field, false);
            $field = new StringField('idciudad');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idzona');
            $this->dataset->AddField($field, false);
            $field = new StringField('saldo_nit_cc');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('cupo_tope');
            $this->dataset->AddField($field, false);
            $field = new StringField('dia_limites_facturas');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('idciudad', '(SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento)', new StringField('idciudad'), new StringField('ciudad', 'idciudad_ciudad', 'idciudad_ciudad_ciudad'), 'idciudad_ciudad_ciudad');
            $this->dataset->AddLookupField('idzona', 'public.zona', new IntegerField('idzona', null, null, true), new StringField('descripcion', 'idzona_descripcion', 'idzona_descripcion_public_zona'), 'idzona_descripcion_public_zona');
            $this->dataset->AddLookupField('saldo_nit_cc', 'public.cliente_saldo_vi', new StringField('nit_cc'), new IntegerField('saldo', 'saldo_nit_cc_saldo', 'saldo_nit_cc_saldo_public_cliente_saldo_vi'), 'saldo_nit_cc_saldo_public_cliente_saldo_vi');
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
                new FilterColumn($this->dataset, 'nit_cc', 'nit_cc', $this->RenderText('NIT/CC')),
                new FilterColumn($this->dataset, 'nombre_completo', 'nombre_completo', $this->RenderText('Nombre Completo')),
                new FilterColumn($this->dataset, 'idciudad', 'idciudad_ciudad', $this->RenderText('')),
                new FilterColumn($this->dataset, 'sexo', 'sexo', $this->RenderText('Sexo')),
                new FilterColumn($this->dataset, 'direccion', 'direccion', $this->RenderText('Direcci�n')),
                new FilterColumn($this->dataset, 'tel_movil', 'tel_movil', $this->RenderText('Tel Movil')),
                new FilterColumn($this->dataset, 'email', 'email', $this->RenderText('Email')),
                new FilterColumn($this->dataset, 'fax', 'fax', $this->RenderText('Fax')),
                new FilterColumn($this->dataset, 'tel_fijo', 'tel_fijo', $this->RenderText('Tel Fijo')),
                new FilterColumn($this->dataset, 'observacion', 'observacion', $this->RenderText('Observacion')),
                new FilterColumn($this->dataset, 'idzona', 'idzona_descripcion', $this->RenderText('Zona')),
                new FilterColumn($this->dataset, 'saldo_nit_cc', 'saldo_nit_cc_saldo', $this->RenderText('Saldo Total')),
                new FilterColumn($this->dataset, 'cupo_tope', 'cupo_tope', $this->RenderText('Tope Cr�dito')),
                new FilterColumn($this->dataset, 'dia_limites_facturas', 'dia_limites_facturas', $this->RenderText('D�a Limites de Pagos'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['nit_cc'])
                ->addColumn($columns['nombre_completo'])
                ->addColumn($columns['idciudad'])
                ->addColumn($columns['sexo'])
                ->addColumn($columns['direccion'])
                ->addColumn($columns['tel_movil'])
                ->addColumn($columns['email'])
                ->addColumn($columns['fax'])
                ->addColumn($columns['tel_fijo'])
                ->addColumn($columns['observacion'])
                ->addColumn($columns['idzona'])
                ->addColumn($columns['cupo_tope'])
                ->addColumn($columns['dia_limites_facturas']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('nit_cc')
                ->setOptionsFor('nombre_completo')
                ->setOptionsFor('idzona');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('nit_cc_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['nit_cc'],
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
            
            $main_editor = new TextEdit('nombre_completo_edit');
            $main_editor->SetMaxLength(70);
            
            $filterBuilder->addColumn(
                $columns['nombre_completo'],
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
            
            $main_editor = new AutocompleteComboBox('idciudad_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idciudad_ciudad_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idciudad', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idciudad_ciudad_search');
            
            $text_editor = new TextEdit('idciudad');
            
            $filterBuilder->addColumn(
                $columns['idciudad'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('sexo');
            $main_editor->SetAllowNullValue(false);
            $main_editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $main_editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            
            $multi_value_select_editor = new MultiValueSelect('sexo');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $filterBuilder->addColumn(
                $columns['sexo'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('direccion_edit');
            $main_editor->SetMaxLength(50);
            
            $filterBuilder->addColumn(
                $columns['direccion'],
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
            
            $main_editor = new TextEdit('tel_movil_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['tel_movil'],
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
            
            $main_editor = new TextEdit('email_edit');
            $main_editor->SetMaxLength(50);
            
            $filterBuilder->addColumn(
                $columns['email'],
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
            
            $main_editor = new TextEdit('fax_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['fax'],
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
            
            $main_editor = new TextEdit('tel_fijo_edit');
            
            $filterBuilder->addColumn(
                $columns['tel_fijo'],
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
            
            $main_editor = new TextEdit('observacion_edit');
            $main_editor->SetMaxLength(100);
            
            $filterBuilder->addColumn(
                $columns['observacion'],
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
            
            $main_editor = new AutocompleteComboBox('idzona_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idzona_descripcion_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idzona', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idzona_descripcion_search');
            
            $filterBuilder->addColumn(
                $columns['idzona'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('cupo_tope_edit');
            
            $filterBuilder->addColumn(
                $columns['cupo_tope'],
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
            
            $main_editor = new TextEdit('dia_limites_facturas_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['dia_limites_facturas'],
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
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new AjaxOperation(OPERATION_VIEW,
                    $this->GetLocalizerCaptions()->GetMessageString('View'),
                    $this->GetLocalizerCaptions()->GetMessageString('View'), $this->dataset,
                    $this->GetModalGridViewHandler(), $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_clientes_public_facturar_ventas detail
            //
            $column = new DetailColumn(array('nit_cc'), 'public.clientes.public.facturar_ventas', 'public_clientes_public_facturar_ventas_handler', $this->dataset, 'Facturas Ventas');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText('Departamento y Ciudad'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Direcci�n', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::DESKTOP);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idzona', 'idzona_descripcion', 'Zona', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo_nit_cc', 'saldo_nit_cc_saldo', 'Saldo Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setAlign('center');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for cupo_tope field
            //
            $column = new NumberViewColumn('cupo_tope', 'cupo_tope', 'Tope Cr�dito', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dia_limites_facturas field
            //
            $column = new TextViewColumn('dia_limites_facturas', 'dia_limites_facturas', 'D�a Limites de Pagos', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Direcci�n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idzona', 'idzona_descripcion', 'Zona', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo_nit_cc', 'saldo_nit_cc_saldo', 'Saldo Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cupo_tope field
            //
            $column = new NumberViewColumn('cupo_tope', 'cupo_tope', 'Tope Cr�dito', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dia_limites_facturas field
            //
            $column = new TextViewColumn('dia_limites_facturas', 'dia_limites_facturas', 'D�a Limites de Pagos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for nit_cc field
            //
            $editor = new TextEdit('nit_cc_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('NIT/CC', 'nit_cc', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nombre_completo field
            //
            $editor = new TextEdit('nombre_completo_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Nombre Completo', 'nombre_completo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idciudad field
            //
            $editor = new MultiLevelComboBoxEditor('idciudad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $editor->setNumberOfValuesToDisplay(20);
            
            $selectQuery = 'SELECT 
              departamento.iddepartamento, 
              departamento.nombre
            FROM 
              public.departamento
            ORDER BY departamento.nombre';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset0 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Departamento_multiselec');
            $field = new StringField('iddepartamento');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'iddepartamento', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'iddepartamento', 'nombre', $this->RenderText('Ciudad'), null);
            
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset1 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $dataset1->AddField($field, true);
            $field = new StringField('iddepartamento');
            $dataset1->AddField($field, false);
            $field = new StringField('ciudad');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idciudad', 'ciudad', new ForeignKeyInfo('iddepartamento', 'iddepartamento'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idciudad', 'ciudad', $this->RenderText(''), new ForeignKeyInfo('iddepartamento', 'iddepartamento'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idciudad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new RadioEdit('sexo_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for direccion field
            //
            $editor = new TextEdit('direccion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Direcci�n', 'direccion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tel_movil field
            //
            $editor = new TextEdit('tel_movil_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Tel Movil', 'tel_movil', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fax field
            //
            $editor = new TextEdit('fax_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Fax', 'fax', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tel_fijo field
            //
            $editor = new TextEdit('tel_fijo_edit');
            $editColumn = new CustomEditColumn('Tel Fijo', 'tel_fijo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for observacion field
            //
            $editor = new TextEdit('observacion_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Observacion', 'observacion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idzona field
            //
            $editor = new AutocompleteComboBox('idzona_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Zona', 'idzona', 'idzona_descripcion', 'edit_idzona_descripcion_search', $editor, $this->dataset, $lookupDataset, 'idzona', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_clientes_idzonaNestedPage::getNestedInsertHandlerName())
            );
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for cupo_tope field
            //
            $editor = new TextEdit('cupo_tope_edit');
            $editColumn = new CustomEditColumn('Tope Cr�dito', 'cupo_tope', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dia_limites_facturas field
            //
            $editor = new TextEdit('dia_limites_facturas_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('D�a Limites de Pagos', 'dia_limites_facturas', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nit_cc field
            //
            $editor = new TextEdit('nit_cc_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('NIT/CC', 'nit_cc', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nombre_completo field
            //
            $editor = new TextEdit('nombre_completo_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Nombre Completo', 'nombre_completo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idciudad field
            //
            $editor = new MultiLevelComboBoxEditor('idciudad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $editor->setNumberOfValuesToDisplay(20);
            
            $selectQuery = 'SELECT 
              departamento.iddepartamento, 
              departamento.nombre
            FROM 
              public.departamento
            ORDER BY departamento.nombre';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset0 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Departamento_multiselec');
            $field = new StringField('iddepartamento');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'iddepartamento', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'iddepartamento', 'nombre', $this->RenderText('Ciudad'), null);
            
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset1 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $dataset1->AddField($field, true);
            $field = new StringField('iddepartamento');
            $dataset1->AddField($field, false);
            $field = new StringField('ciudad');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idciudad', 'ciudad', new ForeignKeyInfo('iddepartamento', 'iddepartamento'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idciudad', 'ciudad', $this->RenderText(''), new ForeignKeyInfo('iddepartamento', 'iddepartamento'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idciudad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new RadioEdit('sexo_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for direccion field
            //
            $editor = new TextEdit('direccion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Direcci�n', 'direccion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tel_movil field
            //
            $editor = new TextEdit('tel_movil_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Tel Movil', 'tel_movil', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fax field
            //
            $editor = new TextEdit('fax_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Fax', 'fax', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tel_fijo field
            //
            $editor = new TextEdit('tel_fijo_edit');
            $editColumn = new CustomEditColumn('Tel Fijo', 'tel_fijo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for observacion field
            //
            $editor = new TextEdit('observacion_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Observacion', 'observacion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idzona field
            //
            $editor = new AutocompleteComboBox('idzona_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Zona', 'idzona', 'idzona_descripcion', 'insert_idzona_descripcion_search', $editor, $this->dataset, $lookupDataset, 'idzona', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_clientes_idzonaNestedPage::getNestedInsertHandlerName())
            );
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cupo_tope field
            //
            $editor = new TextEdit('cupo_tope_edit');
            $editColumn = new CustomEditColumn('Tope Cr�dito', 'cupo_tope', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dia_limites_facturas field
            //
            $editor = new TextEdit('dia_limites_facturas_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('D�a Limites de Pagos', 'dia_limites_facturas', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Direcci�n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idzona', 'idzona_descripcion', 'Zona', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cupo_tope field
            //
            $column = new NumberViewColumn('cupo_tope', 'cupo_tope', 'Tope Cr�dito', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for dia_limites_facturas field
            //
            $column = new TextViewColumn('dia_limites_facturas', 'dia_limites_facturas', 'D�a Limites de Pagos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Direcci�n', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idzona', 'idzona_descripcion', 'Zona', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for cupo_tope field
            //
            $column = new NumberViewColumn('cupo_tope', 'cupo_tope', 'Tope Cr�dito', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for dia_limites_facturas field
            //
            $column = new TextViewColumn('dia_limites_facturas', 'dia_limites_facturas', 'D�a Limites de Pagos', $this->dataset);
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
        
        protected function OnGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
        $layout->setMode(FormLayoutMode::VERTICAL);
                
        $displayGroup = $layout->addGroup('Registrar de Clientes',12);
        
        $displayGroup->addRow()->addCol($columns['nit_cc'],4)->addCol($columns['nombre_completo'],4);
        
        $displayGroup->addRow()->addCol($columns['idciudad'],8)->addCol($columns['idzona'],4);
        
        $displayGroup->addRow()->addCol($columns['sexo'],4)->addCol($columns['direccion'],4)->addCol($columns['tel_movil'],4);
        
        $displayGroup->addRow()->addCol($columns['email'],4)->addCol($columns['fax'],4)->addCol($columns['tel_fijo'],4);
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function GetEnableModalSingleRecordView() { return true; }
    
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
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            
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
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new public_clientes_public_facturar_ventasPage('public_clientes_public_facturar_ventas', $this, array('clientes_nit_cc'), array('nit_cc'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.clientes.public.facturar_ventas'));
            $detailPage->SetTitle('Facturas Ventas');
            $detailPage->SetMenuLabel('Facturas Ventas');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_clientes_public_facturar_ventas_handler');
            $handler = new PageHTTPHandler('public_clientes_public_facturar_ventas_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idzona_descripcion_search', 'idzona', 'descripcion', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, true);
            $field = new StringField('iddepartamento');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ciudad');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idciudad_ciudad_search', 'idciudad', 'ciudad', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, true);
            $field = new StringField('iddepartamento');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ciudad');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idciudad_ciudad_search', 'idciudad', 'ciudad', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idzona_descripcion_search', 'idzona', 'descripcion', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."zona"');
            $field = new IntegerField('idzona', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo_zona');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idzona_descripcion_search', 'idzona', 'descripcion', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            
            new public_clientes_idzonaNestedPage($this, GetCurrentUserGrantForDataSource('public.zona'));
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='sexo'){
            switch ($fieldData) {
                case 1:
                      $customText=' HOMBRE';
            $handled=true;
                      break;
                case 2:
                   $customText=' MUJER';
            $handled=true;
                    break;
                
            }
            }
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='sexo'){
            switch ($fieldData) {
                case 1:
                      $customText=' HOMBRE';
            $handled=true;
                      break;
                case 2:
                   $customText=' MUJER';
            $handled=true;
                    break;
                
            }
            }
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='sexo'){
            switch ($fieldData) {
                case 1:
                      $customText=' HOMBRE';
            $handled=true;
                      break;
                case 2:
                   $customText=' MUJER';
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
            $rowData['saldo_nit_cc']=$rowData['nit_cc'];
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['saldo_nit_cc']=$rowData['nit_cc'];
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            if($success){
            $newFullname=$rowData['nombre_completo'];
            $message="Cliente $newFullname Registrado Exitosamente.";
            $messageDisplayTime=3;
            }else {
            
               $message='HA OCURRIDO UN ERROR AL REALIZAR EL REGISTRO, POR FAVOR VERIFIQUE LA INFORMACI&Oacute;N E INTENTE NUEVAMENTE!';
            
               $messageDisplayTime=3;
               
            }
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            if($success){
            $newFullname=$rowData['nombre_completo'];
            $message="Cliente $newFullname Registrado Exitosamente.";
            $messageDisplayTime=3;
            }else {
            
               $message='HA OCURRIDO UN ERROR AL REALIZAR EL REGISTRO, POR FAVOR VERIFIQUE LA INFORMACI&Oacute;N E INTENTE NUEVAMENTE!';
            
               $messageDisplayTime=3;
               
            }
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
        $Page = new public_clientesPage("public_clientes", "clientes.php", GetCurrentUserGrantForDataSource("public.clientes"), 'UTF-8');
        $Page->SetTitle('Clientes');
        $Page->SetMenuLabel('Clientes');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.clientes"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
