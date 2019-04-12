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
    
    
    
    class public_agotados_viewsPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."agotados_views"');
            $field = new IntegerField('precio_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $this->dataset->AddField($field, false);
            $field = new StringField('nombre');
            $this->dataset->AddField($field, false);
            $field = new StringField('idproducto');
            $this->dataset->AddField($field, true);
            $field = new StringField('marca');
            $this->dataset->AddField($field, false);
            $field = new StringField('img');
            $this->dataset->AddField($field, false);
            $field = new StringField('num_factura');
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
                new FilterColumn($this->dataset, 'idproducto', 'idproducto', $this->RenderText('Ref. Prodcutos')),
                new FilterColumn($this->dataset, 'num_factura', 'num_factura', $this->RenderText('N° Factura Compra')),
                new FilterColumn($this->dataset, 'img', 'img', $this->RenderText('Imagen')),
                new FilterColumn($this->dataset, 'nombre', 'nombre', $this->RenderText('Descripción')),
                new FilterColumn($this->dataset, 'precio_venta', 'precio_venta', $this->RenderText('Precio Venta')),
                new FilterColumn($this->dataset, 'precio_costo', 'precio_costo', $this->RenderText('Precio Costo')),
                new FilterColumn($this->dataset, 'marca', 'marca', $this->RenderText('Marca'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['num_factura'])
                ->addColumn($columns['img'])
                ->addColumn($columns['nombre'])
                ->addColumn($columns['precio_venta'])
                ->addColumn($columns['precio_costo'])
                ->addColumn($columns['marca']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('idproducto_edit');
            
            $filterBuilder->addColumn(
                $columns['idproducto'],
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
            
            $main_editor = new TextEdit('num_factura_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['num_factura'],
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
            
            $main_editor = new TextEdit('img_edit');
            
            $filterBuilder->addColumn(
                $columns['img'],
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
            
            $main_editor = new TextEdit('nombre_edit');
            
            $filterBuilder->addColumn(
                $columns['nombre'],
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
            
            $main_editor = new TextEdit('precio_venta_edit');
            
            $filterBuilder->addColumn(
                $columns['precio_venta'],
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
            
            $main_editor = new TextEdit('precio_costo_edit');
            
            $filterBuilder->addColumn(
                $columns['precio_costo'],
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
            
            $main_editor = new TextEdit('marca_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['marca'],
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
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for idproducto field
            //
            $column = new NumberViewColumn('idproducto', 'idproducto', 'Ref. Prodcutos', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(false);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for img field
            //
            $column = new ExternalImageViewColumn('img', 'img', 'Imagen', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setAlign('center');
            $column->setInlineStyles('height: 60px; width: 60px;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(false);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new NumberViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new NumberViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('marca', 'marca', 'Marca', $this->dataset);
            $column->SetOrderable(false);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new NumberViewColumn('idproducto', 'idproducto', 'Ref. Prodcutos', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for img field
            //
            $column = new ExternalImageViewColumn('img', 'img', 'Imagen', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setInlineStyles('height: 60px; width: 60px;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new NumberViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new NumberViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('marca', 'marca', 'Marca', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new TextEdit('idproducto_edit');
            $editColumn = new CustomEditColumn('Ref. Prodcutos', 'idproducto', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for num_factura field
            //
            $editor = new TextEdit('num_factura_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('N° Factura Compra', 'num_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for img field
            //
            $editor = new TextEdit('img_edit');
            $editColumn = new CustomEditColumn('Imagen', 'img', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nombre field
            //
            $editor = new TextEdit('nombre_edit');
            $editColumn = new CustomEditColumn('Descripción', 'nombre', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_venta field
            //
            $editor = new TextEdit('precio_venta_edit');
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editColumn = new CustomEditColumn('Precio Costo', 'precio_costo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for marca field
            //
            $editor = new TextEdit('marca_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Marca', 'marca', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new TextEdit('idproducto_edit');
            $editColumn = new CustomEditColumn('Ref. Prodcutos', 'idproducto', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for num_factura field
            //
            $editor = new TextEdit('num_factura_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('N° Factura Compra', 'num_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for img field
            //
            $editor = new TextEdit('img_edit');
            $editColumn = new CustomEditColumn('Imagen', 'img', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nombre field
            //
            $editor = new TextEdit('nombre_edit');
            $editColumn = new CustomEditColumn('Descripción', 'nombre', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_venta field
            //
            $editor = new TextEdit('precio_venta_edit');
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editColumn = new CustomEditColumn('Precio Costo', 'precio_costo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for marca field
            //
            $editor = new TextEdit('marca_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Marca', 'marca', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new NumberViewColumn('idproducto', 'idproducto', 'Ref. Prodcutos', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddPrintColumn($column);
            
            //
            // View column for img field
            //
            $column = new ExternalImageViewColumn('img', 'img', 'Imagen', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setAlign('center');
            $column->setInlineStyles('height: 60px; width: 60px;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new NumberViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new NumberViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('marca', 'marca', 'Marca', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new NumberViewColumn('idproducto', 'idproducto', 'Ref. Prodcutos', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddExportColumn($column);
            
            //
            // View column for img field
            //
            $column = new ExternalImageViewColumn('img', 'img', 'Imagen', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setAlign('center');
            $column->setInlineStyles('height: 60px; width: 60px;');
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new NumberViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new NumberViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('marca', 'marca', 'Marca', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new NumberViewColumn('idproducto', 'idproducto', 'Ref. Prodcutos', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddCompareColumn($column);
            
            //
            // View column for img field
            //
            $column = new ExternalImageViewColumn('img', 'img', 'Imagen', $this->dataset);
            $column->setImageHintTemplate('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setAlign('center');
            $column->setInlineStyles('height: 60px; width: 60px;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(false);
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new NumberViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new NumberViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(false);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('marca', 'marca', 'Marca', $this->dataset);
            $column->SetOrderable(false);
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
        
        public function GetEnableModalGridInsert() { return true; }
    
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
        $Page = new public_agotados_viewsPage("public_agotados_views", "agotados_views.php", GetCurrentUserGrantForDataSource("public.agotados_views"), 'UTF-8');
        $Page->SetTitle('Agotados');
        $Page->SetMenuLabel('Agotados ');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.agotados_views"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
