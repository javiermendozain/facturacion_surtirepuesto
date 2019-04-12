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
    
    
    
    class public_facturar_ventas_public_detalle_factura_ventaPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."detalle_factura_venta"');
            $field = new IntegerField('iddetalle', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('cantidad');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_venta_unitario');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('valor_parcial');
            $this->dataset->AddField($field, false);
            $field = new StringField('idproducto');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idventa');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('descuento');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iva');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('aplicar_descuento');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_para_vendedor');
            $this->dataset->AddField($field, false);
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
                new FilterColumn($this->dataset, 'iddetalle', 'iddetalle', $this->RenderText('Iddetalle')),
                new FilterColumn($this->dataset, 'idventa', 'idventa', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'idproducto', 'idproducto_nombre', $this->RenderText('Productos')),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', $this->RenderText('Cantidad')),
                new FilterColumn($this->dataset, 'descuento', 'descuento', $this->RenderText('Descuento en Venta')),
                new FilterColumn($this->dataset, 'iva', 'iva', $this->RenderText('Iva')),
                new FilterColumn($this->dataset, 'precio_venta_unitario', 'precio_venta_unitario', $this->RenderText('Precio Venta')),
                new FilterColumn($this->dataset, 'valor_parcial', 'valor_parcial', $this->RenderText('Valor Parcial')),
                new FilterColumn($this->dataset, 'aplicar_descuento', 'aplicar_descuento', $this->RenderText(' Descuento')),
                new FilterColumn($this->dataset, 'utilidad_para_vendedor', 'utilidad_para_vendedor', $this->RenderText('Utilidad Para Vendedor'))
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
            $columnFilter
                ->setOptionsFor('idproducto');
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
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta', $this->dataset);
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
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
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
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for idventa field
            //
            $editor = new ComboBox('idventa_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editColumn = new CustomEditColumn('N° Factura', 'idventa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento en Venta', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idventa field
            //
            $editor = new ComboBox('idventa_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editColumn = new CustomEditColumn('N° Factura', 'idventa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
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
            $editColumn = new DynamicLookupEditColumn('Productos', 'idproducto', 'idproducto_nombre', 'insert_idproducto_nombre_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'nombre', '%idproducto%, %nombre%,  [ %stock% ]');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('1'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento en Venta', 'descuento', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta_unitario', $editor, $this->dataset);
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
            
            //
            // Edit column for aplicar_descuento field
            //
            $editor = new ComboBox('aplicar_descuento_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('APLICAR'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('NO_APLICAR'));
            $editColumn = new CustomEditColumn(' Descuento', 'aplicar_descuento', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('2'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
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
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
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
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setAllowPrintSelectedRecords(false);
            $this->setExportListAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientEditorValueChangedScript($this->RenderText('/*
            
            
            if (sender.getFieldName() == \'idproducto\')
            {
            include(\'conexion.php\');
            $sql=\'SELECT  precio_venta  FROM productos   WHERE idproducto=293\';
            $rs=pg_query($conn,$sql);
            while($row=pg_fetch_row($rs)) { 
             $valor=$row[0];
             }
            
            $sen= sender.getValue();  
               editors[\'precio_venta_unitario\'].setValue("".valor."");
               // editors[\'precio_venta_unitario\'].enabled(false);  
                $(\'#precio_venta_unitario_edit\').next().show();      
              
            }*/'));
            
            $grid->SetInsertClientFormLoadedScript($this->RenderText('editors[\'iva\'].visible(false);
            editors[\'precio_venta_unitario\'].visible(false);
            editors[\'descuento\'].visible(false);
            editors[\'valor_parcial\'].visible(false);
            editors[\'idventa\'].visible(false);'));
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idproducto_nombre_search', 'idproducto', 'nombre', $this->RenderText('%idproducto%, %nombre%,  [ %stock% ]'), 20);
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
            // Pendiente para Obtener el valor de idventa-> N° Factura
                /*  $estado=$this->GetConnection()->ExecScalarSQL("SELECT 
                          facturar_ventas.estado
                        FROM 
                          public.facturar_ventas
                        WHERE 
                          facturar_ventas.idventa=".$rowData['idventa']."");
                        
                        if($estado==2){
                        $cancel=true;
                        $message='ERROR: NO ESTA PERMITIDO ADICIONAR PRODUCTOS, SI LA FACTURA YA FUE GUARDADA ';
                        $messageDisplayTime=5;   
                        }
                         
            */
                        
                        //------------------------------------------------------BIEN---------------------------------
                        if($rowData['cantidad']==0){
                        $cancel=true;
                        $message=" LA CANTIDAD DEBE SER MAYOR A: 0 ";
                        }
                        
                        include('conexion.php');
                        
                        $sql="SELECT 
                         productos.precio_venta, 
                          productos.iddescuentos,productos.stock
                          ,productos.id_iva, productos.utilidad_para_vendedor
                        FROM 
                          public.productos
                          WHERE productos.idproducto='".$rowData['idproducto']."';";
                           $rs=pg_query($conn,$sql);
                           while($row=pg_fetch_row($rs)) {
                           if($row[2]<$rowData['cantidad']){
                            $cancel=true;
                            $message=" LA CANTIDAD INGRESADA: ".$rowData['cantidad']."  ES MAYOR QUE EL STOCK: ".$row[2]; // number_format() quitar  .00
                          }
                            
                           $rowData['precio_venta_unitario']=$row[0];
                           $rowData['descuento']=$row[1];
                           $rowData['valor_parcial']=$row[0]*$rowData['cantidad'];
                           $rowData['iva']=$row[3];
                           $rowData['utilidad_para_vendedor']=$row[4]*$rowData['cantidad'];
                           }
                           if($rowData['aplicar_descuento']==2){
                        $rowData['descuento']=0;
                        }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            include('conexion.php');
            
            $sql='SELECT estado
              FROM public.facturar_ventas
              WHERE  facturar_ventas.idventa = '.$rowData['idventa'].';';
            
            $rs=pg_query($conn,$sql);
            while($row=pg_fetch_row($rs)) {
            if($row[0]==2){
            $cancel=true;
            $message='ERROR: NO ESTA PERMITIDO ELIMINAR PRODUCTOS, SI LA FACTURA YA FUE GUARDADA ';
            $messageDisplayTime=5;
            }
            }
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include('conexion.php');
                        $precio=$rowData['precio_venta_unitario'];
                        $cantidad=$rowData['cantidad'];
                        $descuento=$rowData['descuento'];
                        $idproducto=$rowData['idproducto'];
                        $subtotal=$cantidad*$precio;
                        
                        
                        $sq='
                        SELECT 
                          facturar_ventas.tipo_de_venta
                        FROM 
                          public.facturar_ventas
                        WHERE 
                          facturar_ventas.idventa = '.$rowData['idventa'].';';
                        $rs=pg_query($conn,$sq);
                        while($row=pg_fetch_row($rs)) { 
                        
                        if($row[0]==1){
                        $sql="UPDATE public.facturar_ventas
                           SET    utilidad_en_venta=utilidad_en_venta+".$subtotal."-((SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".$cantidad."),iva_total=iva_total+(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", 
                           descuento_total=descuento_total+".($cantidad*($precio*($descuento/100))).", 
                                 saldo=0,abono=abono+".$subtotal."-".($cantidad*($precio*($descuento/100))).", sub_total=sub_total+".$subtotal."-(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", valor_total_pagar=valor_total_pagar+".$subtotal."-".($cantidad*($precio*($descuento/100)))."
                         WHERE facturar_ventas.idventa=".$rowData['idventa'].";";
                        
                        }else{
                        
                        $sql="UPDATE public.facturar_ventas
                           SET    utilidad_en_venta=utilidad_en_venta+".$subtotal."-((SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".$cantidad."),iva_total=iva_total+(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", 
                           descuento_total=descuento_total+".($cantidad*($precio*($descuento/100))).", 
                                 saldo=valor_total_pagar+".$subtotal."-".($cantidad*($precio*($descuento/100)))."-(SELECT 
                          facturar_ventas.abono
                        FROM 
                          public.facturar_ventas
                        WHERE 
                          facturar_ventas.idventa = ".$rowData['idventa']."), sub_total=sub_total+".$subtotal."-(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", valor_total_pagar=valor_total_pagar+".$subtotal."-".($cantidad*($precio*($descuento/100)))."
                         WHERE facturar_ventas.idventa=".$rowData['idventa'].";";
                        
                        }
                        $rs=pg_query($conn,$sql);
                        
                        
                        
                        }
                        
                        
                        $sql="UPDATE public.productos
                           SET  stock=stock-".$rowData['cantidad']."
                         WHERE idproducto='".$rowData['idproducto']."';";
                         $rs=pg_query($conn,$sql);
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include('conexion.php');
                        $precio=$rowData['precio_venta_unitario'];
                        $cantidad=$rowData['cantidad'];
                        $descuento=$rowData['descuento'];
                        $idproducto=$rowData['idproducto'];
                        $subtotal=$cantidad*$precio;
                        
                        $sq='
                        SELECT 
                          facturar_ventas.tipo_de_venta
                        FROM 
                          public.facturar_ventas
                        WHERE 
                          facturar_ventas.idventa = '.$rowData['idventa'].';';
                        $rs=pg_query($conn,$sq);
                        
                        while($row=pg_fetch_row($rs)) { 
                        
                        if($row[0]==1){
                        $sql="UPDATE public.facturar_ventas
                           SET    utilidad_en_venta=utilidad_en_venta-(".$subtotal."-((SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".$cantidad.")),iva_total=iva_total-(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", 
                           descuento_total=descuento_total-".($cantidad*($precio*($descuento/100))).", 
                                 saldo=0,abono=abono-".$subtotal."-".($cantidad*($precio*($descuento/100))).", sub_total=sub_total-".$subtotal."+(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", valor_total_pagar=valor_total_pagar-".$subtotal."-".($cantidad*($precio*($descuento/100)))."
                         WHERE facturar_ventas.idventa=".$rowData['idventa'].";";
                        
                        }else{
                        
                        $sql="UPDATE public.facturar_ventas
                           SET    utilidad_en_venta=utilidad_en_venta-(".$subtotal."-((SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".$cantidad.")),iva_total=iva_total-(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", 
                           descuento_total=descuento_total-".($cantidad*($precio*($descuento/100))).", 
                                 saldo=valor_total_pagar-".$subtotal."-".($cantidad*($precio*($descuento/100)))."-(SELECT 
                          facturar_ventas.abono
                        FROM 
                          public.facturar_ventas
                        WHERE 
                          facturar_ventas.idventa = ".$rowData['idventa']."), sub_total=sub_total-".$subtotal."+(SELECT 
                            productos.precio_costo
                        FROM 
                          public.productos
                          WHERE  productos.idproducto='".$idproducto."')*".($cantidad)*($rowData['iva']/100).", valor_total_pagar=valor_total_pagar-".$subtotal."-".($cantidad*($precio*($descuento/100)))."
                         WHERE facturar_ventas.idventa=".$rowData['idventa'].";";
                        
                        }
                        $rs=pg_query($conn,$sql);
                        
                        }
                        
                        
                        // STOCK PRODUCTO
                        $sql="UPDATE public.productos
                           SET  stock=stock+".$rowData['cantidad']."
                         WHERE idproducto='".$rowData['idproducto']."';";
                         $rs=pg_query($conn,$sql);
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
    
    class public_facturar_ventas_clientes_nit_ccNestedPage extends NestedFormPage
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
            $this->dataset->AddLookupField('idciudad', 'public.ciudad', new StringField('idciudad'), new StringField('nombre_ciudad', 'idciudad_nombre_ciudad', 'idciudad_nombre_ciudad_public_ciudad'), 'idciudad_nombre_ciudad_public_ciudad');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nit_cc field
            //
            $editor = new TextEdit('nit_cc_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('NIT / CC', 'nit_cc', $editor, $this->dataset);
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
            // Edit column for direccion field
            //
            $editor = new TextEdit('direccion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Direcci&oacute;n', 'direccion', $editor, $this->dataset);
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
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
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
            // Edit column for observacion field
            //
            $editor = new TextEdit('observacion_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Observaci&oacute;n', 'observacion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tel_fijo field
            //
            $editor = new TextEdit('tel_fijo_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Tel Fijo', 'tel_fijo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idciudad field
            //
            $editor = new AutocompleteComboBox('idciudad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."ciudad"');
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_ciudad');
            $lookupDataset->AddField($field, false);
            $field = new StringField('iddepartamento');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_ciudad', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Ciudad', 'idciudad', 'idciudad_nombre_ciudad', 'insert_idciudad_nombre_ciudad_search', $editor, $this->dataset, $lookupDataset, 'idciudad', 'nombre_ciudad', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idzona field
            //
            $editor = new TextEdit('idzona_edit');
            $editColumn = new CustomEditColumn('Idzona', 'idzona', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cupo_tope field
            //
            $editor = new TextEdit('cupo_tope_edit');
            $editColumn = new CustomEditColumn('Cupo Tope', 'cupo_tope', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dia_limites_facturas field
            //
            $editor = new TextEdit('dia_limites_facturas_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Dia Limites Facturas', 'dia_limites_facturas', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            $rowData['saldo_nit_cc']=$rowData['nit_cc'];
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            if($success)
            {
            $message=' REGISTRO EXITOSO! ';
            $messageDisplayTime=2;
            }else{
            $message='HA OCURRIDO UN ERROR AL REALIZAR EL REGISTRO, POR FAVOR VERIFIQUE LA INFORMACI&Oacute;N E INTENTE NUEVAMENTE!';
            $messageDisplayTime=2;
            }
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class public_facturar_ventasPage extends Page
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
            if (!$this->GetSecurityInfo()->HasAdminGrant())
              $field->SetReadOnly(true, GetApplication()->GetCurrentUserId());
            $this->dataset->AddField($field, false);
            $field = new IntegerField('printt');
            $this->dataset->AddField($field, false);
            $field = new StringField('vendedor');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('forma_pago');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('clientes_nit_cc', 'public.clientes', new StringField('nit_cc'), new StringField('nombre_completo', 'clientes_nit_cc_nombre_completo', 'clientes_nit_cc_nombre_completo_public_clientes'), 'clientes_nit_cc_nombre_completo_public_clientes');
            $this->dataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), '  fecha_hora>\'1999-12-31\' '));
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
                new FilterColumn($this->dataset, 'idventa', 'idventa', $this->RenderText('ID ')),
                new FilterColumn($this->dataset, 'n_factura', 'n_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'clientes_nit_cc', 'clientes_nit_cc_nombre_completo', $this->RenderText('Cliente')),
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha y Hora')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Tipo De Venta')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'descuento_total', 'descuento_total', $this->RenderText('Descuento Total')),
                new FilterColumn($this->dataset, 'iva_total', 'iva_total', $this->RenderText('Iva Total')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total Pagar')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'user_id', 'user_id', $this->RenderText('Vendedor')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado')),
                new FilterColumn($this->dataset, 'printt', 'printt', $this->RenderText('Imprim.')),
                new FilterColumn($this->dataset, 'utilidad_en_venta', 'utilidad_en_venta', $this->RenderText('Utilidad En Venta')),
                new FilterColumn($this->dataset, 'vendedor', 'vendedor', $this->RenderText('Vendedor')),
                new FilterColumn($this->dataset, 'forma_pago', 'forma_pago', $this->RenderText('Forma Pago'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['n_factura'])
                ->addColumn($columns['fecha_hora'])
                ->addColumn($columns['forma_pago']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('clientes_nit_cc')
                ->setOptionsFor('fecha_hora');
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
            
            $main_editor = new AutocompleteComboBox('clientes_nit_cc_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_clientes_nit_cc_nombre_completo_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('clientes_nit_cc', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_clientes_nit_cc_nombre_completo_search');
            
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
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.facturar_ventas.public.detalle_factura_venta')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_facturar_ventas_public_detalle_factura_venta detail
            //
            $column = new DetailColumn(array('idventa'), 'public.facturar_ventas.public.detalle_factura_venta', 'public_facturar_ventas_public_detalle_factura_venta_handler', $this->dataset, 'Detalle Factura Venta');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for idventa field
            //
            $column = new TextViewColumn('idventa', 'idventa', 'ID ', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
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
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
            $column->setInlineStyles('color: white; background-color: green;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('SI EL TIPO DE VENTA FUE A CREDITO EL EXCEDENTE SERA EL SALDO PENDIENTE POR PAGAR  ***PENDIENTE****'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
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
            // View column for idventa field
            //
            $column = new TextViewColumn('idventa', 'idventa', 'ID ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
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
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new AutocompleteComboBox('clientes_nit_cc_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idzona');
            $lookupDataset->AddField($field, false);
            $field = new StringField('saldo_nit_cc');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('cupo_tope');
            $lookupDataset->AddField($field, false);
            $field = new StringField('dia_limites_facturas');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Cliente', 'clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'insert_clientes_nit_cc_nombre_completo_search', $editor, $this->dataset, $lookupDataset, 'nit_cc', 'nombre_completo', '%nit_cc%, %nombre_completo%');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_facturar_ventas_clientes_nit_ccNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i:s a');
            $editColumn = new CustomEditColumn('Fecha y Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->SetInsertDefaultValue($this->RenderText('%CURRENT_DATETIME%'));
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tipo_de_venta field
            //
            $editor = new RadioEdit('tipo_de_venta_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice($this->RenderText('1'), $this->RenderText('DEBITO'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('CREDITO'));
            $editColumn = new CustomEditColumn('Tipo De Venta', 'tipo_de_venta', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('0'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
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
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
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
            // View column for idventa field
            //
            $column = new TextViewColumn('idventa', 'idventa', 'ID ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setAlign('center');
            $column->setInlineStyles('color: white; background-color: green;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
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
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        function GetCustomClientScript()
        {
            return 'function expandFirstDetail() {'. "\n" .
            '  if ($(\'table.pgui-grid\').data(\'grid-class\')){'. "\n" .
            '    var grid = $(\'table.pgui-grid\').data(\'grid-class\');'. "\n" .
            '    var $row =  $(\'table.pgui-grid tr.pg-row\').first(); '. "\n" .
            '    grid.expandDetails($row);'. "\n" .
            '    return true;'. "\n" .
            '  }'. "\n" .
            '  else {'. "\n" .
            '    setTimeout(expandFirstDetail, 100);'. "\n" .
            '  }'. "\n" .
            '}';
        }
        
        function GetOnPageLoadedClientScript()
        {
            return 'expandFirstDetail();';
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
            $result->setEnableRuntimeCustomization(false);
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
            $this->setPrintOneRecordAvailable(false);
            $this->setExportListAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientFormLoadedScript($this->RenderText('editors[\'estado\'].visible(false);
            //editors[\'fecha_hora\'].visible(false);'));
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new public_facturar_ventas_public_detalle_factura_ventaPage('public_facturar_ventas_public_detalle_factura_venta', $this, array('idventa'), array('idventa'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.facturar_ventas.public.detalle_factura_venta'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.facturar_ventas.public.detalle_factura_venta'));
            $detailPage->SetTitle('Detalle Factura Venta');
            $detailPage->SetMenuLabel('Detalle Factura Venta');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_facturar_ventas_public_detalle_factura_venta_handler');
            $handler = new PageHTTPHandler('public_facturar_ventas_public_detalle_factura_venta_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idzona');
            $lookupDataset->AddField($field, false);
            $field = new StringField('saldo_nit_cc');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('cupo_tope');
            $lookupDataset->AddField($field, false);
            $field = new StringField('dia_limites_facturas');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_clientes_nit_cc_nombre_completo_search', 'nit_cc', 'nombre_completo', $this->RenderText('%nit_cc%, %nombre_completo%'), 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idzona');
            $lookupDataset->AddField($field, false);
            $field = new StringField('saldo_nit_cc');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('cupo_tope');
            $lookupDataset->AddField($field, false);
            $field = new StringField('dia_limites_facturas');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_clientes_nit_cc_nombre_completo_search', 'nit_cc', 'nombre_completo', $this->RenderText('%nit_cc%, %nombre_completo%'), 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."ciudad"');
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_ciudad');
            $lookupDataset->AddField($field, false);
            $field = new StringField('iddepartamento');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_ciudad', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idciudad_nombre_ciudad_search', 'idciudad', 'nombre_ciudad', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            new public_facturar_ventas_clientes_nit_ccNestedPage($this, GetCurrentUserGrantForDataSource('public.clientes'));
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CR&Eacute;DITO';
            $handled=true;
                    break;
                
            }
            }
            
            if($fieldName=='forma_pago'){
            switch ($fieldData) {
                case 0:
                      $customText='Efectivo';
            $handled=true;
                      break;
                case 1:
                   $customText='Cr&eacute;dito';
            $handled=true;
                    break;
                case 2:
                    $customText='Otros';
            $handled=true;
                
            }
            }
            
            
            
            
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 1:
                if($rowData['saldo']==0){
                $customText = '<a href="guardar.php?lp='.$rowData['n_factura'].'"><img src="img/save.png" width="20" height="20"></a>';
                $handled=true;
                }else{
                  $customText = '<img src="img/save.png" width="20" height="20">';
                  $handled=true;
                }
                      break;
                case 2:
                $customText = '<img src="img/saved.png" width="20" height="20">';
            $handled=true;
                    break;
                
            }
            }
            
            
            if($fieldName=='printt'){
            if($rowData['estado']==2){
            $customText = '<a href="print.php?lp='.$rowData['n_factura'].'" target="_blank"><img src="img/print.png" width="20" height="20"></a>';
             $handled=true;
            }else{
            $customText = '<img src="img/print.png" width="20" height="20">';
             $handled=true;
            }
             
            }
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CREDITO';
            $handled=true;
                    break;
                
            }
            }
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CREDITO';
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
            //$fecha=date('Y-M-d h:i a');
            
            //$rowData['fecha_hora']=$fecha;
            
            
            $rowData['estado']=1;
            
            
            $rowData['user_id']=$this->GetEnvVar('CURRENT_USER_ID');
            
            if($rowData['tipo_de_venta']==1){
            $rowData['abono']=0;
            }
            
            
            
             //header ("Location: facturar_ventas.php?hname=public_facturar_ventas_public_detalle_factura_venta_handler&fk0=127&master_viewmode=0");
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            if($rowData['tipo_de_venta']==1){
            $rowData['abono']=$rowData['valor_total_pagar'];
            $rowData['saldo']=0;
            
            }else if ($rowData['tipo_de_venta']==2){
            
            include('conexion.php');
            
            $sql='SELECT 
              facturar_ventas.valor_total_pagar
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].';';
            $rs=pg_query($conn,$sql);
              while ($row=pg_fetch_row($rs)) { 
              if($rowData['abono']<=$row[0]){
                $rowData['saldo']=$row[0]-$rowData['abono'];
              }else{
              $cancel=true;
              $message='EL ABONO DEBE SER MENOR O IGUAL QUE EL VALOR TOTAL A PAGAR';
              $messageDisplayTime=15;
              }
              }
            }
            
            
            
            /*
            if($rowData['saldo']==0){
            $rowData['estado']=2;
            }*/
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
        $Page = new public_facturar_ventasPage("public_facturar_ventas", "facturar_ventas.php", GetCurrentUserGrantForDataSource("public.facturar_ventas"), 'UTF-8');
        $Page->SetTitle('Ventas Registrada');
        $Page->SetMenuLabel('Ventas ');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.facturar_ventas"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
