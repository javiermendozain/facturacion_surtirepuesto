<?php

class FilterColumnDynamicSearchHandler
{
    /**
     * @param EngConnection $connection
     * @param BaseSelectCommand $sourceSelect
     * @param ColumnFilterColumn $column
     */
    public function __construct($connection, $sourceSelect, ColumnFilterColumn $column)
    {
        $this->connection = $connection;
        $this->sourceSelect = $sourceSelect;
        $this->column = $column;
    }

    public function Execute()
    {
        $filterColumn = $this->column->getFilterColumn();
        $fieldInfo = $filterColumn->getFieldInfo();
        $displayFieldInfo = $filterColumn->getDisplayFieldInfo();

        $this->sourceSelect->addFieldInfo($fieldInfo);
        $this->sourceSelect->addFieldInfo($displayFieldInfo);
        $this->sourceSelect->addDistinct($fieldInfo->getNameInDataset(), $fieldInfo->FieldType == ftDateTime);
        $this->sourceSelect->setSelects(array(
            $fieldInfo->getNameInDataset(),
            $displayFieldInfo->getNameInDataset()
        ));

        $this->sourceSelect->setOrderBy(array(
            new SortColumn($fieldInfo === $displayFieldInfo ? 1 : 2, $this->column->getOrder()),
        ));

        $getWrapper = ArrayWrapper::createGetWrapper();

        $term = trim($getWrapper->getValue('term', ''));
        if (!empty($term)) {
            $this->sourceSelect->AddFieldFilter(
                $displayFieldInfo->getNameInDataset(),
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );
        }

        $excludedValues = $getWrapper->getValue('excludedValues', array());
        foreach ($excludedValues as $value) {
            $this->sourceSelect->AddFieldFilter($displayFieldInfo->getNameInDataset(), FieldFilter::DoesNotEqual($value, false));
        }

        header('Content-Type: application/json; charset=utf-8');

        $dataReader = $this->connection->CreateDataReader($this->sourceSelect->getSQL(false));
        $dataReader->addFieldInfo($displayFieldInfo);
        $dataReader->addFieldInfo($fieldInfo);

        $dataReader->Open();

        $result = array();
        $valueCount = 0;

        while($dataReader->Next()) {
            $result[] = array(
                'id' => $dataReader->GetFieldValueByName($fieldInfo->getNameInDataset()),
                'value' => $dataReader->GetFieldValueByName($displayFieldInfo->getNameInDataset())
            );

            if (++$valueCount >= $this->column->getNumberOfValuesToDisplay()) {
                break;
            }
        }

        $dataReader->Close();

        echo SystemUtils::ToJSON($result);

        exit;
    }

}