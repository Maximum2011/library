<?php

namespace app\components;

class TransactionService
{
    public function execute(callable $function)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            call_user_func($function);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
} 