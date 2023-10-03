<?php

namespace app\widgets;

use yii\helpers\Html;
use yii\grid\Column;
use yii\grid\DataColumn;

/**
 * Description of GridView
 *
 * @author demiurg
 */
class GridView extends \yii\grid\GridView {
    public $headers = [];
    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableMultipleHeaders()
    {

        $content = '';
        foreach ($this->headers as $row) {
            $rowCells = [];
            foreach ($row as $col) {
                if (isset($col['column'])) {
                    $idx = $col['column'];
                    /* @var $column Column */
                    $column = $this->columns[$idx];
                    $options = array_merge($column->headerOptions, $col['headerOptions'] ?? []);
                    $column->headerOptions = $options;
                }
                else {
                    $column = \Yii::createObject(array_merge([
                        'class' => $this->dataColumnClass ? : DataColumn::class,
                        'grid' => $this,
                    ], $col));
                }

                $rowCells [] = $column->renderHeaderCell();
            }
            $content .= Html::tag('tr', implode('', $rowCells), $this->headerRowOptions);
        }
        
        return $content;
    }
    
    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableSingleHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

        return $content;
    }
    
    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $content = '';
        
        if ($this->headers) {
            $content = $this->renderTableMultipleHeaders();
        }
        else {
            $content = $this->renderTableSingleHeader();
        }
        
        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }
}
