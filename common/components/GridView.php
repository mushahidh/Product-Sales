<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use Yii;
use Closure;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\BaseListView;
use yii\base\Model;
use \yii\grid\DataColumn;
use yii\grid\GridViewAsset;
use \common\models\Voucher;

/**
 * The GridView widget is used to display data in a grid.
 *
 * It provides features like [[sorter|sorting]], [[pager|paging]] and also [[filterModel|filtering]] the data.
 *
 * A basic usage looks like the following:
 *
 * ```php
 * <?= GridView::widget([
 *     'dataProvider' => $dataProvider,
 *     'columns' => [
 *         'id',
 *         'name',
 *         'created_at:datetime',
 *         // ...
 *     ],
 * ]) ?>
 * ```
 *
 * The columns of the grid table are configured in terms of [[Column]] classes,
 * which are configured via [[columns]].
 *
 * The look and feel of a grid view can be customized using the large amount of properties.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridView extends BaseListView {

    const FILTER_POS_HEADER = 'header';
    const FILTER_POS_FOOTER = 'footer';
    const FILTER_POS_BODY = 'body';

    public $table;
    public $group_by;
    public $debit_credit_column;
    public $debit_total;
    public $credit_total;
    public $debit_opening;
    public $credit_opening;

    /**
     * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
     * Defaults to 'yii\grid\DataColumn'.
     */
    public $dataColumnClass;

    /**
     * @var string the caption of the grid table
     * @see captionOptions
     */
    public $caption;

    /**
     * @var array the HTML attributes for the caption element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @see caption
     */
    public $captionOptions = [];

    /**
     * @var array the HTML attributes for the grid table element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $tableOptions = ['class' => 'table table-striped table-bordered'];

    /**
     * @var array the HTML attributes for the container tag of the grid view.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'grid-view'];

    /**
     * @var array the HTML attributes for the table header row.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $headerRowOptions = [];

    /**
     * @var array the HTML attributes for the table footer row.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $footerRowOptions = [];

    /**
     * @var array|Closure the HTML attributes for the table body rows. This can be either an array
     * specifying the common HTML attributes for all body rows, or an anonymous function that
     * returns an array of the HTML attributes. The anonymous function will be called once for every
     * data model returned by [[dataProvider]]. It should have the following signature:
     *
     * ```php
     * function ($model, $key, $index, $grid)
     * ```
     *
     * - `$model`: the current data model being rendered
     * - `$key`: the key value associated with the current data model
     * - `$index`: the zero-based index of the data model in the model array returned by [[dataProvider]]
     * - `$grid`: the GridView object
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $rowOptions = [];

    /**
     * @var Closure an anonymous function that is called once BEFORE rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $beforeRow;

    /**
     * @var Closure an anonymous function that is called once AFTER rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $afterRow;

    /**
     * @var boolean whether to show the header section of the grid table.
     */
    public $showHeader = true;

    /**
     * @var boolean whether to show the footer section of the grid table.
     */
    public $showFooter = false;

    /**
     * @var boolean whether to show the grid view if [[dataProvider]] returns no data.
     */
    public $showOnEmpty = true;

    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;

    /**
     * @var array grid column configuration. Each array element represents the configuration
     * for one particular grid column. For example,
     *
     * ```php
     * [
     *     ['class' => SerialColumn::className()],
     *     [
     *         'class' => DataColumn::className(), // this line is optional
     *         'attribute' => 'name',
     *         'format' => 'text',
     *         'label' => 'Name',
     *     ],
     *     ['class' => CheckboxColumn::className()],
     * ]
     * ```
     *
     * If a column is of class [[DataColumn]], the "class" element can be omitted.
     *
     * As a shortcut format, a string may be used to specify the configuration of a data column
     * which only contains [[DataColumn::attribute|attribute]], [[DataColumn::format|format]],
     * and/or [[DataColumn::label|label]] options: `"attribute:format:label"`.
     * For example, the above "name" column can also be specified as: `"name:text:Name"`.
     * Both "format" and "label" are optional. They will take default values if absent.
     *
     * Using the shortcut format the configuration for columns in simple cases would look like this:
     *
     * ```php
     * [
     *     'id',
     *     'amount:currency:Total Amount',
     *     'created_at:datetime',
     * ]
     * ```
     *
     * When using a [[dataProvider]] with active records, you can also display values from related records,
     * e.g. the `name` attribute of the `author` relation:
     *
     * ```php
     * // shortcut syntax
     * 'author.name',
     * // full syntax
     * [
     *     'attribute' => 'author.name',
     *     // ...
     * ]
     * ```
     */
    public $columns = [];

    /**
     * @var string the HTML display when the content of a cell is empty.
     * This property is used to render cells that have no defined content,
     * e.g. empty footer or filter cells.
     *
     * Note that this is not used by the [[DataColumn]] if a data item is `null`. In that case
     * the [[\yii\i18n\Formatter::nullDisplay|nullDisplay]] property of the [[formatter]] will
     * be used to indicate an empty data value.
     */
    public $emptyCell = '&nbsp;';

    /**
     * @var \yii\base\Model the model that keeps the user-entered filter data. When this property is set,
     * the grid view will enable column-based filtering. Each data column by default will display a text field
     * at the top that users can fill in to filter the data.
     *
     * Note that in order to show an input field for filtering, a column must have its [[DataColumn::attribute]]
     * property set or have [[DataColumn::filter]] set as the HTML code for the input field.
     *
     * When this property is not set (null) the filtering feature is disabled.
     */
    public $filterModel;

    /**
     * @var string|array the URL for returning the filtering result. [[Url::to()]] will be called to
     * normalize the URL. If not set, the current controller action will be used.
     * When the user makes change to any filter input, the current filtering inputs will be appended
     * as GET parameters to this URL.
     */
    public $filterUrl;

    /**
     * @var string additional jQuery selector for selecting filter input fields
     */
    public $filterSelector;

    /**
     * @var string whether the filters should be displayed in the grid view. Valid values include:
     *
     * - [[FILTER_POS_HEADER]]: the filters will be displayed on top of each column's header cell.
     * - [[FILTER_POS_BODY]]: the filters will be displayed right below each column's header cell.
     * - [[FILTER_POS_FOOTER]]: the filters will be displayed below each column's footer cell.
     */
    public $filterPosition = self::FILTER_POS_BODY;

    /**
     * @var array the HTML attributes for the filter row element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $filterRowOptions = ['class' => 'filters'];

    /**
     * @var array the options for rendering the filter error summary.
     * Please refer to [[Html::errorSummary()]] for more details about how to specify the options.
     * @see renderErrors()
     */
    public $filterErrorSummaryOptions = ['class' => 'error-summary'];

    /**
     * @var array the options for rendering every filter error message.
     * This is mainly used by [[Html::error()]] when rendering an error message next to every filter input field.
     */
    public $filterErrorOptions = ['class' => 'help-block'];

    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "{summary}\n{items}\n{pager}";

    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate [[columns]] objects.
     */
    public function init() {
        parent::init();
        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        if (!isset($this->filterRowOptions['id'])) {
            $this->filterRowOptions['id'] = $this->options['id'] . '-filters';
        }
        $this->initColumns();
    }

    /**
     * Runs the widget.
     */
    public function run() {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        GridViewAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiGridView($options);");
        parent::run();
    }

    /**
     * Renders validator errors of filter model.
     * @return string the rendering result.
     */
    public function renderErrors() {
        if ($this->filterModel instanceof Model && $this->filterModel->hasErrors()) {
            return Html::errorSummary($this->filterModel, $this->filterErrorSummaryOptions);
        } else {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name) {
        switch ($name) {
            case "{errors}":
                return $this->renderErrors();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Returns the options for the grid view JS widget.
     * @return array the options
     */
    protected function getClientOptions() {
        $filterUrl = isset($this->filterUrl) ? $this->filterUrl : Yii::$app->request->url;
        $id = $this->filterRowOptions['id'];
        $filterSelector = "#$id input, #$id select";
        if (isset($this->filterSelector)) {
            $filterSelector .= ', ' . $this->filterSelector;
        }

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
        ];
    }

    /**
     * Renders the data models for the grid view.
     */
    public function renderItems() {
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();
        $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableFooter,
            $tableBody,
        ]);

        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }

    /**
     * Renders the caption element.
     * @return bool|string the rendered caption element or `false` if no caption element should be rendered.
     */
    public function renderCaption() {
        if (!empty($this->caption)) {
            return Html::tag('caption', $this->caption, $this->captionOptions);
        } else {
            return false;
        }
    }

    /**
     * Renders the column group HTML.
     * @return bool|string the column group HTML or `false` if no column group should be rendered.
     */
    public function renderColumnGroup() {
        $requireColumnGroup = false;
        foreach ($this->columns as $column) {
            /* @var $column Column */
            if (!empty($column->options)) {
                $requireColumnGroup = true;
                break;
            }
        }
        if ($requireColumnGroup) {
            $cols = [];
            foreach ($this->columns as $column) {
                $cols[] = Html::tag('col', '', $column->options);
            }

            return Html::tag('colgroup', implode("\n", $cols));
        } else {
            return false;
        }
    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader() {
        $where = [];

        $start_date = $this->filterModel->start_date;
        
        $where = $this->dataProvider->query->where;

        $where_opening = [];
        $count = 0;
        //var_dump($where);
        //$where_opening[0]='and';
        $where_opening[] = 'and';
        if (!empty($where) && is_array($where))
            foreach ($where as $key => $attr) {
                $count++;
                if (is_array($attr)) {
                    if ($attr[0] == '>=' && $attr[1] == '[date]') {
                    } else {
                        $where_opening[] = $attr;
                    }
                }
            }

        $opening_debit = (new db\Query)->from('gl')->where(['=','type',0]);
        $opening_credit = (new db\Query)->from('gl')->where(['=','type',1]);
        //$opening->where($where_opening);
        if(!empty($this->filterModel->start_date)){
            $opening_debit->andWhere(["<","date",$this->filterModel->start_date]);
            $opening_credit->andWhere(["<","date",$this->filterModel->start_date]);
        }else{
            //$opening_debit->andWhere("1=2");
            //$opening_credit->andWhere("1=2");
        };
        if(!empty($this->filterModel->end_date)){
            //$opening->andWhere(["<=","date",$this->filterModel->end_date]);
        }
        if(!empty($this->filterModel->department_code)){
            $opening_debit->andWhere(["=","department_code",$this->filterModel->department_code]);
            $opening_credit->andWhere(["=","department_code",$this->filterModel->department_code]);
        }
        if(!empty($this->filterModel->fund_branch_code)){
            $opening_debit->andWhere(["=","fund_branch_code",$this->filterModel->fund_branch_code]);
            $opening_credit->andWhere(["=","fund_branch_code",$this->filterModel->fund_branch_code]);
        }
        if(!empty($this->filterModel->product_project_code)){
            $opening_debit->andWhere(["=","product_project_code",$this->filterModel->product_project_code]);
            $opening_credit->andWhere(["=","product_project_code",$this->filterModel->product_project_code]);
        }
        $controls = \common\models\Category::findOne(['ID'=>$this->filterModel->category_code]);
        if(!empty($this->filterModel->category_code) && $controls){
            //var_dump($control->code);
            $opening_debit->andWhere("chart_of_accounts_code like "."'".$controls->code."%'");
            $opening_credit->andWhere("chart_of_accounts_code like "."'".$controls->code."%'");
        }
        $sub_controls = \common\models\SubCategory::findOne(['ID'=>$this->filterModel->sub_category_code]);
        if(!empty($this->filterModel->sub_category_code) && $sub_controls){
            //var_dump($control->code);
            $opening_debit->andWhere("chart_of_accounts_code like "."'".$sub_controls->code."%'");
            $opening_credit->andWhere("chart_of_accounts_code like "."'".$sub_controls->code."%'");
        }
        if (!empty($this->filterModel->chart_of_accounts_code)) {
           $opening_debit->andWhere(['=','chart_of_accounts_code',$this->filterModel->chart_of_accounts_code]);
           $opening_credit->andWhere(['=','chart_of_accounts_code',$this->filterModel->chart_of_accounts_code]);
        }
        if(empty($this->filterModel->start_date)){
            $opening_debit = 0.0;
            $opening_credit = 0.0;
        }else{
            $opening_debit = $opening_debit->sum('amount');
            $opening_credit = $opening_credit->sum('amount');
        }
        
        if ($opening_debit < $opening_credit) {
            $this->credit_opening = $opening_credit-$opening_debit;
            $opening_cr = "Opening Balance: ".number_format($this->credit_opening,2);
            $opening_dr = "";
            $this->debit_opening = 0.0;
        } else {
            $this->debit_opening = $opening_debit-$opening_credit;
            $opening_dr = "Opening Balance: ".number_format($this->debit_opening,2);
            $opening_cr = "";
            $this->credit_opening = 0.0;
        }
        
        //var_dump($start_date);

        $return = "<thead>\n" . "<tr><th style='width:50%;'>Debit</td><th style='width:50%;'>Credit</th>" . "\n</tr>";
        $return.="<tr>";
        $return.="<td class='opening-tr'><span>$opening_dr</span></td>";
        $return.="<td class='opening-tr'><span>$opening_cr</span></td>";
        $return.="</tr>";
        $return.="</thead>";
        return $return;
    }

    /**
     * Renders the table footer.
     * @return string the rendering result.
     */
    public function renderTableFooter() {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_FOOTER) {
            $content .= $this->renderFilters();
        }

        return "<tfoot>\n" . $content . "\n</tfoot>";
    }

    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters() {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column Column */
                $cells[] = $column->renderFilterCell();
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }

    /**
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody() {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }
        $balance = $this->debit_opening+$this->debit_total-$this->credit_opening - $this->credit_total;
        if ($balance>0) {
            //debit balance
            $debit_total = "Carried Forward: " . number_format(abs($balance), 2);
            $credit_total = "";
        }else{
            $credit_total = "Carried Forward: " . number_format(abs($balance), 2);
            $debit_total = "";
        }
        $footer_total_row = "<tr class='footer-total'><td>$credit_total</td><td>$debit_total</td></tr>";

        if (empty($rows)) {
            $colspan = count($this->columns);

            return "<tbody>\n<tr><td style='text-align:center;padding:20px;' colspan=\"2\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody>\n" . implode("\n", $rows) . $footer_total_row . "\n</tbody>";
        }
    }

    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderCellDetail($where, $dr_cr, $model) {
        $filterModel = $this->filterModel;

        $cat = \common\models\Category::find()->where(['=', 'ID', $this->filterModel->category_code])->one();
        $subcat = \common\models\SubCategory::find()->where(['=', 'ID', $this->filterModel->sub_category_code])->one();
        $cat_code = isset($cat->code) ? $cat->code : "";
        $sub_cat_code = isset($subcat->code) ? $subcat->code : "";
        //$gl_entries = (new \yii\db\Query())->from('gl')->where($where)->andWhere($this->debit_credit_column . "=" . $dr_cr . " and date='" . $model->date . "'")->andWhere("chart_of_accounts_code like '".$cat_code."%'")->andWhere("chart_of_accounts_code like '".$sub_cat_code."%'");
        $gl_entries = \common\models\Gl::find()->where($where)->andWhere($this->debit_credit_column . "=" . $dr_cr . " and date='" . $model->date . "'")->andWhere("chart_of_accounts_code like '" . $cat_code . "%'")->andWhere("chart_of_accounts_code like '" . $sub_cat_code . "%'");
        if (!empty($this->filterModel->chart_of_accounts_code)) {
            $gl_entries->andWhere(['=', 'chart_of_accounts_code', $this->filterModel->chart_of_accounts_code]);
        }
        if (!empty($this->filterModel->department_code)) {
            $gl_entries->andWhere(['=', 'department_code', $this->filterModel->department_code]);
        }
        if (!empty($this->filterModel->fund_branch_code)) {
            $gl_entries->andWhere(['=', 'fund_branch_code', $this->filterModel->fund_branch_code]);
        }
        if (!empty($this->filterModel->product_project_code)) {
            $gl_entries->andWhere(['=', 'product_project_code', $this->filterModel->product_project_code]);
        }
        $gl_entries = $gl_entries->all();
        $return = "<table style='width:100%;'>";
        foreach ($gl_entries as $gl) {
            if ($dr_cr == 0) {
                $this->debit_total+=$gl['amount'];
            } else {
                $this->credit_total+=$gl['amount'];
            }
            $account = \common\models\ChartOfAccounts::findOne(['code' => $gl["chart_of_accounts_code"]]);
            $account_code = $gl["chart_of_accounts_code"];
            if ($account) {
                $account_code = $gl["chart_of_accounts_code"] . ":" . $account->short_name;
            }
            $dr_cr_json = json_encode($dr_cr);
            $model_date_json = json_encode($model->date);
            $run_detail  = empty($gl['run_ID'])?"":"<br/>".$gl['added_from'];
            $run_detail_link  = empty($gl['run_ID'])?"":$gl['run_ID'];
            if($gl['document_type']=='Voucher'){
                $v = \common\models\Voucher::findOne($gl['document_ID']);
                $run_detail = $v->voucher_style ."-". $v->sr;
            }
            $return.='<tr style="border-bottom: solid 1px #eee; padding: 2px;"><td style="width:120px;font-size: 12px;font-family: monospace; font-weight: bold;text-transform: capitalize;">' . $gl['document_type'] . ' ' .$run_detail. '</td><td style="text-align:left;font-family: monospace;">' . $account_code . '</td><td><a data-where=' . $account_code . ' data-drcr=' . $dr_cr_json . ' data-date=' . $model_date_json . ' data-voucher="' . $gl['document_ID'] .'" data-run-link="' . $run_detail_link . '"  onclick="openSupporting(this)" data>' . number_format($gl["amount"], 2) . '</a></td></tr>';
        }
        $return.="</table>";
        return $return;
    }

    public function renderTableRow($model, $key, $index) {
        $cells = [];
        $where = $this->dataProvider->query->where;

        /* @var $column Column */
        //foreach ($this->columns as $column) {
        //var_dump($model);
        $cells[0] = '<td style="font-weight:bold;">' . $model->date . '<div class="cell-dr-deails" style="font-weight:normal;">' . $this->renderCellDetail($where, 0, $model) . '</div></td>';
        $cells[1] = '<td style="font-weight:bold;">' . $model->date . '<div class="cell-cr-deails" style="font-weight:normal;">' . $this->renderCellDetail($where, 1, $model) . '</div></td>';
        //}
        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        return Html::tag('tr', implode('', $cells), $options);
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns() {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                            'class' => $this->dataColumnClass ? : DataColumn::className(),
                            'grid' => $this,
                                        ], $column));
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * Creates a [[DataColumn]] object based on a string in the format of "attribute:format:label".
     * @param string $text the column specification string
     * @return DataColumn the column instance
     * @throws InvalidConfigException if the column specification is invalid
     */
    protected function createDataColumn($text) {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
                    'class' => $this->dataColumnClass ? : DataColumn::className(),
                    'grid' => $this,
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'text',
                    'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function guessColumns() {
        $models = $this->dataProvider->getModels();
        $model = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $this->columns[] = $name;
            }
        }
    }

}
