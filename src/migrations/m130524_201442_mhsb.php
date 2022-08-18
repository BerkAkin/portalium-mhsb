<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_mhsb extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_company', [
            'id' => $this->primaryKey(11)->notNull(),
            'name' => $this->string(128)->notNull(),
            'def_id' => $this->integer(11)->notNull(),
            'domain' => $this->string(32)->notNull(),
            'email' => $this->string(32)->notNull(),
            'group' => $this->tinyInteger(3)->notNull(),
            'tax_id_number' => $this->string(13)->notNull(),
            'tax_department' => $this->string(64)->notNull(),
            'tax_country' => $this->string(2)->notNull(),
            'address' => $this->text()->notNull(),
            'status' => $this->tinyInteger(1)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_bank', [
            'id' => $this->primaryKey(11)->notNull(),
            'company_id' => $this->integer(11)->notNull(),
            'name' => $this->string(64)->notNull(),
            'number' => $this->integer(11)->notNull(),
            'currency_code' => $this->integer(11)->notNull(),
            'opening_balance' => $this->integer(11)->notNull(),
            'current_balance' => $this->integer(11)->notNull(),
            'bank_name' => $this->string(64)->notNull(),
            'bank_phone' => $this->integer(11)->notNull(),
            'bank_address' => $this->text()->notNull(),
            'status' => $this->tinyInteger(1)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_definition', [
            'id' => $this->primaryKey(11)->notNull(),
            'name' => $this->string(64)->notNull(),
            'parent'=> $this->integer(11),
            'code' => $this->string(32),
            'type' => "ENUM('paycol','payment', 'collecting', 'currency', 'company','expense', 'revenue', 'stock', 'service','sale', 'purchase', 'vendor', 'cusven', 'card', 'label', 'selfemployment', 'expenseslip')",
            'created_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_document', [
            'id' => $this->primaryKey(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'company_id' => $this->integer(11)->notNull(),
            'category_id' => $this->integer(11)->defaultValue(null),
            'no' => $this->string(32)->notNull(),
            'def_id' => $this->integer(11)->notNull(),
            'currency_id' => $this->integer(11)->defaultValue('1')->notNull(),
            'rate' => $this->float(7,2)->defaultValue(null),
            'description' => $this->text(),
            'payment' => $this->integer(11)->defaultValue(null),
            'date' => $this->dateTime()->defaultValue(null),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_item', [
            'id' => $this->primaryKey(11)->notNull(),
            'name' => $this->string(128)->notNull(),
            'document_id' => $this->integer(11)->notNull(),
            'def_id' => $this->integer(11)->notNull(),
            'quantity' => $this->smallInteger(6)->defaultValue(null),
            'price' => $this->decimal(7,2)->defaultValue(null),
            'tax' => $this->integer(3)->defaultValue('0'),
            'income_tax' => $this->integer(3)->defaultValue('0'),
            'net_wage' => $this->integer(11)->defaultValue(null),
            'stoppage' => $this->integer(3)->defaultValue(null),
            'collected' => $this->integer(11)->defaultValue(null),
            'total' => $this->integer(11)->defaultValue('0'),
            'is_included_tax' => $this->tinyInteger(1)->defaultValue('0'),
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('mhsb_transaction', [
            'id' => $this->primaryKey(11)->notNull(),
            'company_id' => $this->integer(11)->notNull(),
            'type_id' => $this->integer(11)->notNull(),
            'amount' => $this->decimal(7,2)->notNull(),
            'currency_id' => $this->integer(11)->defaultValue('1')->notNull(),
            'rate' => $this->float(7,2)->defaultValue('1.00'),
            'date' => $this->dateTime()->defaultValue(null),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")
        ], $tableOptions);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('mhsb_upload', [
            'id' => $this->primaryKey(11)->notNull(),
            'floatValue' => $this->integer(11)->notNull(),
            'textValue' => $this->string(128)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'def_id',
            'mhsb_company',
            'def_id'
        );

        $this->createIndex(
            'def_id',
            'mhsb_document',
            'def_id'
        );
        $this->createIndex(
            'company_id',
            'mhsb_document',
            'company_id'
        );
        $this->createIndex(
            'category_id',
            'mhsb_document',
            'category_id'
        );
        $this->createIndex(
            'currency_id',
            'mhsb_document',
            'currency_id'
        );

        $this->createIndex(
            'idx_transcation_items',
            'mhsb_item',
            'document_id'
        );
        $this->createIndex(
            'def_id',
            'mhsb_item',
            'def_id'
        );

        $this->createIndex(
            'type_id',
            'mhsb_transaction',
            'type_id'
        );
        $this->createIndex(
            'company_id',
            'mhsb_transaction',
            'company_id'
        );
        $this->createIndex(
            'currency_id',
            'mhsb_transaction',
            'currency_id'
        );
        $this->createIndex(
            'company_id',
            'mhsb_bank',
            'company_id'
        );
        $this->createIndex(
            'currency_code',
            'mhsb_bank',
            'currency_code'
        );
        $this->createIndex(
            'parent',
            'mhsb_definition',
            'parent'
        );


        $this->addForeignKey(
            'fk1_definitions_companies',
            'mhsb_company',
            'def_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk1_companies_documents',
            'mhsb_document',
            'company_id',
            'mhsb_company',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk1_definitions_documents',
            'mhsb_document',
            'def_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk2_definitions_documents',
            'mhsb_document',
            'currency_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk1_definitions_items',
            'mhsb_item',
            'def_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk1_documents_items',
            'mhsb_item',
            'document_id',
            'mhsb_document',
            'id'
        );

        $this->addForeignKey(
            'fk1_companies_transactions',
            'mhsb_transaction',
            'company_id',
            'mhsb_company',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk1_definitions_transactions',
            'mhsb_transaction',
            'currency_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk2_definitions_transactions',
            'mhsb_transaction',
            'type_id',
            'mhsb_definition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_company_id',
            'mhsb_bank',
            'company_id',
            'mhsb_company',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_currency_id',
            'mhsb_bank',
            'currency_code',
            'mhsb_definition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_bank_id',
            'mhsb_definition',
            'parent',
            'mhsb_bank',
            'id',
            'CASCADE'
        );


        $this->batchInsert('mhsb_definition',['name', 'code', 'type'],[
            ['Türk Lirası', 'TL', 'currency'],
            ['Serbest Meslek Makbuzu', NULL, 'selfemployment'],
            ['Gider Pusulası', NULL, 'expenseslip'],
            ['Amerikan Doları', 'USD', 'currency'],
            ['Mal Alım Faturası', NULL, 'purchase'],
            ['Mal Satış Faturası', NULL, 'sale'],
            ['Akaryakıt Giderleri', '770.40.10005', 'expense'],
            ['Nakit', NULL, 'payment'],
            ['Banka Havalesi', NULL, 'payment'],
            ['Kredi Kartı', NULL, 'payment'],
            ['Çek', NULL, 'payment'],
            ['Senet', NULL, 'payment'],
            ['Diğer', NULL, 'payment'],
            ['Ortak Ödeme Tufan Silier', NULL, 'payment'],
            ['Nakit', NULL, 'collecting'],
            ['Banka Havalesi', NULL, 'collecting'],
            ['Kredi Kartı', NULL, 'collecting'],
            ['Çek', NULL, 'collecting'],
            ['Senet', NULL, 'collecting'],
            ['Diğer', NULL, 'collecting'],
            ['Ortak Ödeme Tufan Silier', NULL, 'collecting'],
            ['Avukat Vekalet Ücret Ve Giderleri', '770.50.50004', 'expense'],
            ['Bahçe Ve Saha Bakım Onarım Giderleri', '770.40.20001', 'expense'],
            ['Banka Masraf Ve Giderleri', '770.50.10004', 'expense'],
            ['Baskılı Evrak Matbaa Giderleri', '770.50.10002', 'expense'],
            ['Belge Harçları', '770.60.10009', 'expense'],
            ['Bina Giderlerine Katılım Payları', '770.50.70001', 'expense'],
            ['Bina Ve Arazi Vergileri', '770.60.10001', 'expense'],
            ['Büro Kira Giderleri', '770.50.30005', 'expense'],
            ['Çevre Temizlik Vergisi', '770.60.10004', 'expense'],
            ['Dava Harç Ve Giderleri', '770.50.50002', 'expense'],
            ['Demirbaşlar Bakım Onarım Giderleri', '770.40.20005', 'expense'],
            ['Depo Kira Giderleri', '770.50.30006', 'expense'],
            ['Dışarıya Yaptırılan İşçilikler', '770.40.30008', 'expense'],
            ['Diğer Taşeronlardan Alına Hizmetler', '770.40.30007', 'expense'],
            ['Diğer Taşeronlardan Alına Hizmetler', '770.40.30007', 'expense'],
            ['Doğalgaz Giderleri', '770.40.10002', 'expense'],
            ['Sigorta Giderleri', '770.50.20002', 'expense'],
            ['Eğitim, Sempozyum, Seminer  Giderleri', '770.50.60003', 'expense'],
            ['Elektrik Giderleri', '770.40.10001', 'expense'],
            ['Gazete, Dergi, Neşriyat Abonelik Gideri', '770.50.60005', 'expense'],
            ['Genel Sevk Ve İdare Hizmet Giderleri', '770.40.40007', 'expense'],
            ['Giyim Ve Koruyucu Avadanlıklar', '770.10.10003', 'expense'],
            ['Güvenlik Hizmet Giderleri', '770.40.30006', 'expense'],
            ['Hukuk Danışmanlığı Giderleri', '770.40.40001', 'expense'],
            ['Isıtma Ve Aydınlatma Giderleri', '770.50.90005', 'expense'],
            ['İcra Harç Ve Giderleri', '770.50.50003', 'expense'],
            ['İlan Ve Reklam Vergisi', '770.60.10006', 'expense'],
            ['İnternet / İletişim Giderleri', '770.40.50003', 'expense'],
            ['İş Makineleri Bakım Onarım Giderleri', '770.40.20006', 'expense'],
            ['İş Makineleri Kira Giderleri', '770.50.30003', 'expense'],
            ['Kırtasiye Ve Bilgisayar Sarf Malzemeleri', '770.10.10004', 'expense'],
            ['Köprü,Otoyol Ve Otopark Giderleri', '770.50.90007', 'expense'],
            ['Lpg Giderleri', '770.40.10003', 'expense'],
            ['Makbuz Karşılığı Damga Vergisi', '770.60.10003', 'expense'],
            ['Mali Danışmanlık Giderleri', '770.40.40002', 'expense'],
            ['Mali Mesuliyet Sigorta Giderleri', '770.50.20006', 'expense'],
            ['Misafir Ağırlama Giderleri', '770.50.60001', 'expense'],
            ['Motorlu Taşıt Vergileri', '770.60.10002', 'expense'],
            ['Nakliyat Sigorta Giderleri', '770.50.20004', 'expense'],
            ['Nakliye Hammaliye Hizmet Giderleri', '770.40.30004', 'expense'],
            ['Noter Harç Ve Giderleri', '770.50.50001', 'expense'],
            ['Odalar Yıllık Aidatları', '770.50.90003', 'expense'],
            ['Onarım Bakım Malzemeleri', '770.10.10001', 'expense'],
            ['Personel Sağlık Hayat Sigorta Giderleri', '770.50.20005', 'expense'],
            ['Personel Taşıma Hizmet Giderleri', '770.40.30001', 'expense'],
            ['Posta/Kargo Giderleri', '770.40.50002', 'expense'],
            ['Sergi, Fuar Giderlerine Katılım Payları', '770.50.70002', 'expense'],
            ['Su Giderleri', '770.40.10004', 'expense'],
            ['Şehiriçi İş Takip Ve Yol Giderleri', '770.50.90006', 'expense'],
            ['Tapu Harçları', '770.60.10008', 'expense'],
            ['Taşıtlar Bakım Onarım Giderleri', '770.40.20004', 'expense'],
            ['Taşıtlar Kira Giderleri', '770.50.30004', 'expense'],
            ['Taşıtlar Sigorta Giderleri', '770.50.20003', 'expense'],
            ['Teknik İşler Danışmanlık Giderleri', '770.40.40004', 'expense'],
            ['Telefon Giderleri', '770.40.50001', 'expense'],
            ['Telif, Tercüme, Yayın Giderleri', '770.50.60006', 'expense'],
            ['Temizlik Hizmet Giderleri', '770.40.30002', 'expense'],
            ['Temsil Giderleri', '770.50.60004', 'expense'],
            ['Tesis Bakım Onarım Giderleri', '770.40.20003', 'expense'],
            ['Tesis,Makine Ve Cihaz Kira Giderleri', '770.50.30002', 'expense'],
            ['Toplantı Giderleri', '770.50.60002', 'expense'],
            ['Yemek Parası', '770.20.30004', 'expense'],
            ['Yol Parası', '770.20.30003', 'expense'],
            ['Yurtdışı Seyahat Giderleri', '770.50.40004', 'expense'],
            ['Yurtiçi Seyahat Giderleri', '770.50.40002', 'expense'],
            ['Gider Yazılan Demirbaş', '770.50.40003', 'expense'],
            ['Yükleme-Boşaltma Hizmet Giderleri', '770.40.30003', 'expense'],
            ['Hammaddde Alışları', '150', 'expense'],
            ['% 00 KDV Lİ HİZMET SATIŞLARI', '600.40.10000', 'revenue'],
            ['% 01 KDV Lİ HİZMET SATIŞLARI', '600.40.10001', 'revenue'],
            ['% 08 KDV Lİ HİZMET SATIŞLARI', '600.40.10008', 'revenue'],
            ['% 18 KDV Lİ HİZMET SATIŞLARI', '600.40.10018', 'revenue'],
            ['% 00 KDV Lİ HİZMET SATIŞLARI', '600.52.10000', 'revenue'],
            ['% 01 KDV Lİ MAMÜL SATIŞLARI', '600.52.10001', 'revenue'],
            ['% 08 KDV Lİ MAMÜL SATIŞLARI', '600.52.10008', 'revenue'],
            ['% 18 KDV Lİ MAMÜL SATIŞLARI', '600.52.10018', 'revenue'],
            ['% 00 KDV Lİ HİZMET SATIŞLARI', '600.53.10000', 'revenue'],
            ['% 01 KDV Lİ TİCARİ MAL SATIŞLARI', '600.53.10001', 'revenue'],
            ['% 08 KDV Lİ TİCARİ MAL SATIŞLARI', '600.53.10008', 'revenue'],
            ['% 18 KDV Lİ TİCARİ MAL SATIŞLARI', '600.53.10018', 'revenue'],
            ['% 01 KDV Lİ 2/10 TAVKİFATLI HİZMET SATIŞLARI', '600.62.40001', 'revenue'],
            ['% 08 KDV Lİ 2/10 TAVKİFATLI HİZMET SATIŞLARI', '600.62.40008', 'revenue'],
            ['% 18 KDV Lİ 2/10 TAVKİFATLI HİZMET SATIŞLARI', '600.62.40018', 'revenue'],
            ['% 01 KDV Lİ 2/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.62.52001', 'revenue'],
            ['% 08 KDV Lİ 2/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.62.52008', 'revenue'],
            ['% 18 KDV Lİ 2/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.62.52018', 'revenue'],
            ['% 01 KDV Lİ 2/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.62.53001', 'revenue'],
            ['% 08 KDV Lİ 2/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.62.53008', 'revenue'],
            ['% 18 KDV Lİ 2/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.62.53018', 'revenue'],
            ['% 01 KDV Lİ 5/10 TAVKİFATLI HİZMET SATIŞLARI', '600.65.40001', 'revenue'],
            ['% 08 KDV Lİ 5/10 TAVKİFATLI HİZMET SATIŞLARI', '600.65.40008', 'revenue'],
            ['% 18 KDV Lİ 5/10 TAVKİFATLI HİZMET SATIŞLARI', '600.65.40018', 'revenue'],
            ['% 01 KDV Lİ 5/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.65.52001', 'revenue'],
            ['% 08 KDV Lİ 5/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.65.52008', 'revenue'],
            ['% 18 KDV Lİ 5/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.65.52018', 'revenue'],
            ['% 01 KDV Lİ 5/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.65.53001', 'revenue'],
            ['% 08 KDV Lİ 5/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.65.53008', 'revenue'],
            ['% 18 KDV Lİ 5/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.65.53018', 'revenue'],
            ['% 01 KDV Lİ 7/10 TAVKİFATLI HİZMET SATIŞLARI', '600.67.40001', 'revenue'],
            ['% 08 KDV Lİ 7/10 TAVKİFATLI HİZMET SATIŞLARI', '600.67.40008', 'revenue'],
            ['% 18 KDV Lİ 7/10 TAVKİFATLI HİZMET SATIŞLARI', '600.67.40018', 'revenue'],
            ['% 01 KDV Lİ 7/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.67.52001', 'revenue'],
            ['% 08 KDV Lİ 7/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.67.52008', 'revenue'],
            ['% 18 KDV Lİ 7/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.67.52018', 'revenue'],
            ['% 01 KDV Lİ 7/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.67.53001', 'revenue'],
            ['% 08 KDV Lİ 7/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.67.53008', 'revenue'],
            ['% 18 KDV Lİ 7/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.67.53018', 'revenue'],
            ['% 01 KDV Lİ 9/10 TAVKİFATLI HİZMET SATIŞLARI', '600.69.40001', 'revenue'],
            ['% 08 KDV Lİ 9/10 TAVKİFATLI HİZMET SATIŞLARI', '600.69.40008', 'revenue'],
            ['% 18 KDV Lİ 9/10 TAVKİFATLI HİZMET SATIŞLARI', '600.69.40018', 'revenue'],
            ['% 01 KDV Lİ 9/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.69.52001', 'revenue'],
            ['% 08 KDV Lİ 9/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.69.52008', 'revenue'],
            ['% 18 KDV Lİ 9/10 TAVKİFATLI MAMÜL SATIŞLARI', '600.69.52018', 'revenue'],
            ['% 01 KDV Lİ 9/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.69.53001', 'revenue'],
            ['% 08 KDV Lİ 9/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.69.53008', 'revenue'],
            ['% 18 KDV Lİ 9/10 TAVKİFATLI TİCARİ MAL SATIŞLARI', '600.69.53018', 'revenue'],
            ['YURTDIŞI SATIŞLAR', '601.10.10001', 'revenue'],
            ['Hizmet Alım Faturası', NULL, 'purchase'],
            ['Yazar Kasa Fişi', NULL, 'purchase'],
            ['Alınan Serbest Meslek Mak.', NULL, 'purchase'],
            ['Alınan İADE Faturası', NULL, 'purchase'],
            ['Hizmet Satış Faturası', NULL, 'sale'],
            ['Verilen İADE Faturası', NULL, 'sale'],
            ['Euro', 'EUR', 'currency'],
            ['İngiliz Sterlini', 'GBP', 'currency'],
            ['Japon Yeni', 'JPY', 'currency'],
            ['Kanada Doları', 'CAD', 'currency'],
            ['Şahıs Şirketi',NULL,'company'],
            ['Limited Şirket',NULL,'company'],
            ['Anonim Şirket',NULL,'company'],
            ['Deneme', NULL, 'label'],
        ]);
        //şahıs şirketi
        /* $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%samples}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(200)->notNull(),
			'description' => $this->text()->notNull(),
            'picture' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%sample_data}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sample_id' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx_sample_data_sample_id-1',
            'sample_data',
            'sample_id'
        );

        $this->addForeignKey(
          'fk_sample_data_sample_id-1',
          'sample_data',
          'sample_id',
          'samples',
          'id'
        ); */

    }

    public function down()
    {
        $this->dropForeignKey('fk_quiz_id','mhsb_attempt');
        $this->dropForeignKey('fk1_definitions_companies','mhsb_company');
        $this->dropForeignKey('fk1_companies_documents','mhsb_document');
        $this->dropForeignKey('fk1_definitions_documents','mhsb_document');
        $this->dropForeignKey('fk2_definitions_documents','mhsb_document');
        $this->dropForeignKey('fk1_definitions_items','mhsb_item');
        $this->dropForeignKey('fk1_documents_items','mhsb_item');
        $this->dropForeignKey('fk1_companies_transactions','mhsb_transaction');
        $this->dropForeignKey('fk1_definitions_transactions','mhsb_transaction');
        $this->dropForeignKey('fk2_definitions_transactions','mhsb_transaction');
        $this->dropForeignKey('fk_currency_id','mhsb_bank');
        $this->dropForeignKey('fk_company_id','mhsb_bank');
        $this->dropForeignKey('fk_bank_id','mhsb_definition');

        $this->dropTable('mhsb_attempt');
        $this->dropTable('mhsb_quiz');
        $this->dropTable('mhsb_company');
        $this->dropTable('mhsb_definition');
        $this->dropTable('mhsb_document');
        $this->dropTable('mhsb_item');
        $this->dropTable('mhsb_transaction');
        $this->dropTable('mhsb_bank');


        /* $this->dropForeignKey('fk_sample_data_sample_id-1','sample_data');
        $this->dropIndex('idx_sample_data_sample_id-1','sample_data');
        $this->dropTable('{{%sample_data}}');
        $this->dropTable('{{%samples}}'); */
    }
}
