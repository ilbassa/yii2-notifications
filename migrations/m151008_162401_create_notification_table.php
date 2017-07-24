<?php

use yii\db\Migration;

class m151008_162401_create_notification_table extends Migration
{
    const TABLE_NAME = '{{%notification}}';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'key_id' => $this->integer(),
            'type' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'seen' => $this->boolean()->notNull(),
            'created_at' => $this->date()->notNull(),
        ]);

        /* ORACLE DOESN'T CREATE AUTOMATICALLY AUTOINCREMENT ON PRIMARYKEY() */
        if($this->db->driverName=='oci'){
        	$createSequenceSql = <<< SQL
			CREATE SEQUENCE SEQ_NOTIFICATION_ID
                                    INCREMENT BY 1
                                    MAXVALUE 9999999999999999999999999999
                                    NOMINVALUE
                                    NOORDER
                                    NOCYCLE
                                    NOCACHE
SQL;
        	$this->execute($createSequenceSql);
        	
	        $createTriggerSql = <<< SQL
				CREATE TRIGGER TRI_NOTIFICATION_ID
				   BEFORE INSERT
				   ON {{%notification}}
				   FOR EACH ROW
				BEGIN
				      :new.{{id}} := SEQ_NOTIFICATION_ID.NEXTVAL;
				END;
SQL;
	        $this->execute($createTriggerSql);
        
        }
    }

    public function down()
    {
    	if($this->db->driverName=='oci'){
	    	$this->execute('DROP TRIGGER TRI_NOTIFICATION_ID');
	    	$this->execute('DROP SEQUENCE SEQ_NOTIFICATION_ID');
    	}
        $this->dropTable(self::TABLE_NAME);
    }
}
