<?php

namespace common\models\helpers;

use Yii;
use yii\db\Query;

class Statistics extends \yii\base\Model
{
    public static function CurrentLevel($user_id)
    {
        $current_level = (new Query())
            ->select('users_level.display_name as current_level')
            ->from('user')
            ->innerJoin('users_level', 'user.user_level_id = users_level.id')
            ->where(['=', 'user.id', $user_id])
            ->one();
        return $current_level['current_level'];
    }
    public static function CurrentStock($user_id)
    {
        $current_stock = (new Query())
            ->select('SUM(remaining_quantity) as current_stock')
            ->from('stock_in')
            ->where(['=', 'user_id', $user_id])
            ->groupby(['product_id'])
            ->one();
        return $current_stock['current_stock'];
    }
    public static function CurrentProfit($user_id)
    {
        $stock_in_remaning_price = (new Query())
            ->select('SUM(initial_quantity * price) -  SUM(remaining_quantity * price) as stock_in_price')
            ->from('stock_in')
            ->where(['=', 'user_id', $user_id])
            ->one();

        $total_buying_price = (new Query())
            ->select('SUM(product_order.order_price * product_order.quantity) as buying_price ')
            ->from('order')
            ->innerJoin('product_order', 'product_order.order_id = order.id')
            ->where(['=', 'order.user_id', $user_id])
            ->andWhere(['=', 'order.status', '1'])
            ->one();

        $total_return_price = (new Query())
            ->select('SUM(product_order.order_price * product_order.quantity) as return_price')
            ->from('order')
            ->innerJoin('product_order', 'product_order.order_id = order.id')
            ->where(['=', 'order.user_id', $user_id])
            ->andWhere(['=', 'order.status', '4'])
            ->one();
        $total_sale_price = (new Query())
            ->select('SUM(product_order.order_price * product_order.quantity) as return_price')
            ->from('order')
            ->innerJoin('product_order', 'product_order.order_id = order.id')
            ->where(['=', 'order.order_request_id', $user_id])
            ->andWhere(['=', 'order.status', '1'])
            ->one();
        $total_price = floatval($total_buying_price['buying_price']) - floatval($total_return_price['return_price']);
        $total_purcahse_price = $total_price; //- floatval($stock_in_remaning_price['stock_in_price']);
        return floatval($total_sale_price) - $total_purcahse_price;
    }
    public static function TotalSales($user_id)
    {
        $userDetail = \common\models\User::findOne(['id', $user_id]);
        $levelId = $userDetail->user_level_id;
        $management_level_id = array_search('Management Team', \common\models\Lookup::$user_levels);
        $Role = Yii::$app->authManager->getRolesByUser($user_id);
        if (!(isset($Role['super_admin']) || $management_level_id == $levelId)) {
            try
            {
                $children_ids = (new Query())
                    ->select('GetFamilyTree(`id`) as children') //->select('`id`, `parent_id`,GetFamilyTree(`id`) as children ')
                    ->from('user')
                    ->where(['=', 'id', $user_id])->one()['children'];

                $children_ids = explode(',', $children_ids);
            } catch (\Exception $e) {
            }
            $children_ids[] = $user_id;
        }
        // $account_receivables = \common\models\Account::find()->select('id')->where(['account_type' => array_search('Receivable', \common\models\Lookup::$account_types)]);
        // if (!(isset($Role['super_admin']) || $management_level_id == $levelId)) {
        //     $account_receivables->andWhere(['in', 'user_id', $children_ids]);
        // }
        // $account_receivables = $account_receivables->asArray()->all();
        // $account_receivables = array_column($account_receivables, 'id');
        // $sales_amount = (new Query())
        //     ->select('sum(amount) amount')
        //     ->from('gl')
        //     ->where(['in', 'account_id', $account_receivables])->one();

        //return $sales_amount['amount'];

        $sales_quantity = (new Query())
            ->select('(sum(initial_quantity) - sum(remaining_quantity)) as quantity')
            ->from('stock_in');
        if (!(isset($Role['super_admin']) || $management_level_id == $levelId)) {
            $sales_quantity->where(['in', 'user_id', $children_ids]);
        }
        $sales_quantity = $sales_quantity->one();
        return $sales_quantity['quantity'];
    }

    public static function TotalSalesClientRequest($user_id)
    {
        $approved_status = array_search('Approved', \common\models\Lookup::$status);
        $userDetail = \common\models\User::findOne(['id', $user_id]);
        $levelId = $userDetail->user_level_id;
        $management_level_id = array_search('Management Team', \common\models\Lookup::$user_levels);
        $Role = Yii::$app->authManager->getRolesByUser($user_id);
        $start_date = date('Y-m-01', strtotime(date('Y-m-d')));
        $end_date = date('Y-m-t', strtotime(date('Y-m-d')));
        $admin_id = 1;
        try
        {
            $total_sales = (new Query())
                ->select('SUM(product_order.quantity) as quantity')
                ->from('order')
                ->innerJoin('product_order', 'product_order.order_id = order.id')
                ->where(['=', 'order.status', $approved_status])
                ->andWhere(['>=', 'order.created_at', $start_date])
                ->andWhere(['<=', 'order.created_at', $end_date]);
            if ((isset($Role['super_admin']) || $management_level_id == $levelId)) {
                $MrUsers = (new Query())
                    ->select('id')
                    ->from('user')
                    ->where(['=', 'user_level_id', $management_level_id])
                    ->indexby('id')
                    ->all();
                $MrUsers = array_keys($MrUsers);
                $total_sales->andWhere(['=', 'order.order_request_id', $admin_id])
                    ->andWhere(['in', 'order.user_id', $MrUsers]);
            } else {
                $total_sales->andWhere(['in', 'order.user_id', $user_id])
                    ->andWhere(['=', 'order.order_request_id', $userDetail->parent_id]);
            }

            $total_sales=$total_sales->one();
            return $total_sales['quantity'];
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function CurrentUser($user_id)
    {
        try
        {

            $children_ids = (new Query())
                ->select('GetFamilyTree(`id`) as children')
                ->from('user')
                ->where(['=', 'id', $user_id])->one()['children'];
            $children_ids = explode(',', $children_ids);
            return count($children_ids);
        } catch (Exception $ex) {
            return 0;
        }
    }

    public static function CurrentRemaning($user_id, $urent_users)
    {
        $total_user = (new Query())
            ->select('*')
            ->from('user')
            ->innerJoin('users_level', 'users_level.id = user.user_level_id')
            ->where(['=', 'user.id', $user_id])
            ->one();
        return $total_user['max_user'] - $urent_users;
    }
    public static function CurrentUserLimit($user_id)
    {
        $level_id = \common\models\User::findOne(['id' => $user_id]);
        $level_limit = \common\models\UsersLevel::find()->where(['parent_id' => $level_id->user_level_id])->andWhere(['!=', 'max_user', '-1'])->one();
        if ($level_limit) {
            return $level_limit->max_user;
        } else {
            return 'infinite';
        }
    }
    public static function LimitUser($user_id)
    {
        $level_id = \common\models\User::findOne(['id' => $user_id]);
        $next_level = \common\models\Lookup::$next_levels['' . $level_id->user_level_id];
        return \common\models\User::find()->where(['user_level_id' => $next_level])->andWhere(['parent_id' => $user_id])->andWhere(['!=', 'company_user', '1'])->count();
    }
    public static function allStatusDashboard($user_id)
    {
        $all_status = array();
        $return_approved = array_search('Return Approved', \common\models\Lookup::$status);
        $pending = array_search('Pending', \common\models\Lookup::$status);
        $approved = array_search('Approved', \common\models\Lookup::$status);
        $transfer_approved = array_search('Transfer Approved', \common\models\Lookup::$status);
        $all_status['current_level'] = Statistics::CurrentLevel($user_id);
        $all_status['current_stock'] = Statistics::CurrentStock($user_id);
        $all_status['current_profit'] = Statistics::CurrentProfit($user_id);
        $all_status['current_user'] = Statistics::CurrentUser($user_id);
        $all_status['user_limit'] = Statistics::CurrentUserLimit($user_id);
        if ($all_status['user_limit'] != 'infinite') {
            $limit_user = Statistics::LimitUser($user_id);
            $all_status['user_limit'] = $all_status['user_limit'] - $limit_user;
        }
        $all_status['user_remning'] = Statistics::CurrentRemaning($user_id, $all_status['current_user']);
        $all_status['total_sales'] = Statistics::TotalSalesClientRequest($user_id);
        $all_status['total_order'] = \common\models\Order::find()->where(['user_id' => $user_id])->count();
        $all_status['pending_order'] = \common\models\Order::find()->where(['order_request_id' => $user_id])->andWhere(['status' => $pending])->count();
        $all_status['approved_order'] = \common\models\Order::find()->where(['order_request_id' => $user_id])->andWhere(['status' => $approved])->count();
        $all_status['transfered_order'] = \common\models\Order::find()->where(['order_request_id' => $user_id])->andWhere(['status' => $transfer_approved])->count();
        $all_status['returned_order'] = \common\models\Order::find()->where(['order_request_id' => $user_id])->andWhere(['status' => $return_approved])->count();

        return $all_status;
    }

}
