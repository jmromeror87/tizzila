<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateCustomersSeeder extends Seeder
{
    public function run(): void
    {
        // Mapeo tipdoc del sistema viejo → código DIAN
        $tipdoc = [
            'CC' => '13', // Cédula de Ciudadanía
            'NI' => '31', // NIT
            'CE' => '22', // Cédula de Extranjería
            'TI' => '12', // Tarjeta de Identidad
            'PA' => '91', // Pasaporte
        ];

        // tipo_org: 1=Persona Natural, 2=Persona Jurídica
        $tipoOrg = [
            '1' => '2', // Persona Natural
            '2' => '1', // Persona Jurídica
        ];

        // regimen: 1=Simplificado/No Responsable, 2=Común/Responsable IVA
        $tipoRegimen = [
            '1' => '49', // No responsable IVA
            '2' => '48', // Responsable IVA
        ];

        $clientes = [
            ['1098803063','8','CC','1','1','ACEVEDO VERA SILVIA MANUELA','CLL 16 16 18','vetehoriz50@gmail.com','3115862999','68689','0','CO'],
            ['901206865','8','NI','2','2','AGRO VETERINARIA LOS TRES POTRILLOS S.A.S','CLL 1 4 02 ESQ LT 1 BR CHAPINERO','YOSELYG1665@GMAIL.COM','3185530009','54172','0','CO'],
            ['901443068','1','NI','2','2','AGROCOL SUPERIOR S.A.S','CL 14 0 50 BR MOTILONES','agrocolsuperior@gmail.com','3107782657','54001','0','CO'],
            ['901711674','3','NI','2','2','AGROINDUSTRIA COLOMBIANA E INVERSIONES SAS','KM 1 VIA PALENQUE CHIMITA CR 3 2 203 LC 204','agropecuariachimita@gmail.com','3173932881','68307','0','CO'],
            ['901498674','1','NI','2','2','AGROINVERSIONES COMARCA S.A.S','AVENIDA DEL RIO 25N 90 IN3 44 URB VEGAS DEL RIO','admin@agricomarca.com','3015035108','54001','0','CO'],
            ['901074409','4','NI','2','2','AGROPECUARIA EL ROBLE DE CHINACOTA S.A.S','CRA 4 2 86 BR CENTRO','facturacionagroelroblesas@outlook.com','3112194608','54172','0','CO'],
            ['901424996','9','NI','2','2','AGROPECUARIA MAILU SAS','CA KDX 510 250 LABRANZA','agropecuariamailu@gmail.com','3138919276','54003','0','CO'],
            ['901243904','4','NI','2','2','AGROPQUIMICOS S.A.S','LT 2 VILLAS DE COROZAL','lizcol_1@hotmail.com','3118129490','54405','0','CO'],
            ['901215885','3','NI','2','2','AGROVETERINARIA SUPERCAMPO LM SAS','CL 5 4 50','laurapatriciapalomino@hotmail.com','3114829533','68575','0','CO'],
            ['46646878','1','CC','1','1','AGUDELO CHAVARRO MARIANA','CRA 3A 13 72','linacabrales0812@gmail.com','3114653256','15572','0','CO'],
            ['1100220085','3','CC','1','1','AGUILAR REMOLINA LEYSON FERNANDO','CL 70 10 85','acosta1934@hotmail.com','3115932450','68001','0','CO'],
            ['27673199','2','CC','1','2','ALBARRACIN BARRERA ROSA INES','CL 2 3 24','facturacioncasaganaderaelportal@hotmail.com','3114056468','54223','0','CO'],
            ['1095803617','7','CC','1','1','ALFONSO ROMERO EDINSON FERNANDO','CLL 13 05 01 BR SANTA ANA','edinsonf.1990@gmail.com','3135807240','68276','0','CO'],
            ['901557191','8','NI','2','2','ALIMENTARIA SANCHEZ S.A.S','AV 0 A 12 05 ED INGRID OF 306 BR LA PLAYA','leonardo21332309@gmail.com','3025744582','54001','0','CO'],
            ['1095830768','5','CC','1','1','ALMEIDA GUTIERREZ THALIA YURLEY','AV 17 7W 51 BR BLANCO','thaliayalmeidag@hotmail.com','3158175167','68547','0','CO'],
            ['28217826','1','CC','1','1','ALVARADO ZAMBRANO ADRIANA','VRD LA ESMERALDA FCA JAVERIANA','adriana-az@hotmail.com','3156239607','68406','0','CO'],
            ['1094167349','5','CC','1','1','ALVAREZ CABALLERO NIURKA GESSENIA','CL 4 B 9BN 105 BR CHAPINERO','niurkaalvarez07@gmail.com','3144051382','54001','0','CO'],
            ['1094860781','6','CC','1','1','ALVAREZ MANSILLA FREDDY ANTONIO','AV 1 BARRIO LIMONAR TRIGAL DEL NORTE','almafred1978@gmail.com','3002616706','54001','0','CO'],
            ['1064840734','5','CC','1','1','ALVAREZ RUEDA NILSON','CA KDX K2-212','nilsonalru94@gmail.com','3202107001','54250','0','CO'],
            ['1098626269','9','CC','1','1','AMADO SUAREZ FERNANDO','KM 4 VIA A RIONEGRO FINCA EL LIMONCITO','fernando.amado.su@gmail.com','3158600369','68001','0','CO'],
            ['37227720','5','CC','1','2','ANGARITA DE ROPERO OFELIA ROSA','CL 14 #0-50','yamirop123@gmail.com','75786422','54001','0','CO'],
            ['37328476','6','CC','1','1','ARENIZ CONTRERAS YEINY SUJEY','CALLE 8A #14-35','yeiny_areniz@hotmail.com','3158318169','54498','0','CO'],
            ['1084728958','2','CC','1','1','ASAN JIMENEZ EUNICE','CLL 10 #2-23','euniceasan17@gmail.com','3207533996','05579','0','CO'],
            ['901340281','1','NI','2','2','AVICOLA ABUNDIA SAS','CA KDX 197-730 VDA RINCONADA','abundiasas@hotmail.com','3112541668','54498','0','CO'],
            ['901968717','5','NI','2','2','AVICOLA JJ SAS','CALLE 12 No. 0E-117','avicolajj25@gmail.com','3148765419','54001','0','CO'],
            ['901521653','3','NI','2','2','AVICOLA JUNIOR SANTAMARIA JH S.A.S','MZ 22 LT 308 LC 1 BARRIO VIDELSO','avicolajuniorjh@gmail.com','3108720803','54405','0','CO'],
            ['900928406','5','NI','2','2','AVICOLA JUNIOR SANTAMARIA S.A.S','M Z 22 LT 308 URBANIZACION VIDELSO','contabilidad@avicolajunior.co','3209515254','54405','0','CO'],
            ['901484168','3','NI','2','2','AVICOLA LOZAVES SAS','KDK 93 JUAN FRIO','avicolalozaves@hotmail.com','3164943000','54874','0','CO'],
            ['901672694','2','NI','2','2','AVICOLA SAN LUIS EBB S.A.S','CRA 26 #18-38 LC 100','AVICOLASANLUISEBB@GMAIL.COM','3125233376','68001','0','CO'],
            ['901795742','6','NI','2','2','AVICOLA SAN PABLITO S.A.S','FINCA SAN PABLITO VEREDA SAN PABLO','AVICOLASANPABLITO2024@GMAIL.COM','3158008137','68615','0','CO'],
            ['901786620','8','NI','2','2','AVICOLA SAN SEBASTIAN BT S.A.S','VEREDA EL MESTIZO KDX 8','AGROSANSEBASTIANBT@GMAIL.COM','3173003438','54261','0','CO'],
            ['901627606','3','NI','2','2','AVICOLA TECHO DE PIEDRA S.A.S B.I.C','VEREDA LIMON DULCE FINCA EL PROGRESO','AVICOLATECHODEPIEDRA@GMAIL.COM','3003825349','15218','0','CO'],
            ['901608347','1','NI','2','2','AVICULTURA LA VERDOLAGA S.A.S','VIA URIMACO SEC MADRE TIERRA','aviculturasas@gmail.com','3214204544','54001','0','CO'],
            ['902031993','3','NI','2','2','AVINGO S.A.S','AV 8 No 5-60','AVINGOSAS@GMAIL.COM','3232841957','54001','0','CO'],
            ['901375718','7','NI','2','2','AVINORT LRM S.A.S','AVE 4 #7-67 BARRIO EL CENTRO','avicolaavinort@hotmail.com','3176467165','54001','0','CO'],
            ['1091666739','4','CC','1','1','BARBOSA DURAN CAMILO','CALLE 6 # 25-60 BARRIO 20 JULIO','emirobarbosa@hotmail.com','3186937776','54498','0','CO'],
            ['13363066','0','CC','1','1','BARBOSA RINCON EMIRO','CALLE 6 #25-60 BARRIO CALLE NUEVA','emirobarbosa@hotmail.com','3115870010','54498','0','CO'],
            ['91219826','1','CC','1','1','BASTO HERNANDEZ GABRIEL','CL 4 #15C-27 BARRIO SAN CRISTOBAL ETAPA 1','gabrielbastoavicola@gmail.com','3204696174','68547','0','CO'],
            ['13499516','8','CC','1','1','BAUTISTA CARREÑO LUIS FERNANDO','CALLE 7 #20A-38 FERRAGRO LA VILLA','mglwygy@hotmail.com','','54874','0','CO'],
            ['1095926141','1','CC','1','1','BAUTISTA MARTINEZ FREDY ARTURO','DG 12 #15-46 BARRIO VILLAMPIS','fredybautistamvz@hotmail.com','3164844324','68307','0','CO'],
            ['37330761','7','CC','1','1','BAYONA NAVARRO AMPARO','CRA 14 # 9-10','ampabana.19@hotmail.com','','54498','0','CO'],
            ['1005084946','1','CC','1','1','BAYONA ORTIZ JHOAN ARLEY','CL 1 NORTE #9E-55 CA BARRIO QUINTA ORIENTAL','johanbayona2017@gmail.com','3028599626','54001','0','CO'],
            ['1093748747','3','CC','1','1','BAYONA TORRES EMILIANA','CL 49 # 9-11 TORRE ARKAMAR APTO 303','agrosansebastianbt@gmail.com','3173003438','54405','0','CO'],
            ['1095962616','0','CC','1','1','BELTRAN MORALES JONATHAN DANILO','AV 7 CL 2 BN CA 42 APTO 201 BARRIO COLPET','agromaxcotasjyg.col@gmail.com','3505888458','54001','0','CO'],
            ['901912739','6','NI','2','2','BIOAGRO OG S.A.S','KDX 18 VEREDA AGUA CLARA','BIOAGRO.OG@GMAIL.COM','3102498769','54001','0','CO'],
            ['901596267','5','NI','2','2','BIOALIMENTOS OG S.A.S','VDA KDX 26 1A ALTO GUARAMITO','BIOALIMENTOS2022@HOTMAIL.COM','3138559492','54001','0','CO'],
            ['1098677722','2','CC','1','1','BLANCO TOTAITIVE LEIDY MARCELA','CRA 12 #12-37 BARRIO CENTRO','George660@hotmail.com','3166243724','68406','0','CO'],
            ['1093786438','4','CC','1','1','BOHORQUEZ MARILEZ','CALLE 16 #0-49 BARRIO MOTILONES','','3125840384','54001','0','CO'],
            ['1002063844','8','CC','1','1','BOTERO RAMIREZ JOHN EDISON','PD RURAL KDX 554 BOCONO FINCA LA ESTANCIA','jhonbotero18@gmail.com','3223100060','54874','0','CO'],
            ['1038093016','5','CC','1','1','BOTERO ZAPATA JUAN CAMILO','KDX 554 BARRIO BOCONO','camilobzapata@hotmail.com','3113217621','54874','0','CO'],
            ['1102373888','4','CC','1','1','BUENAHORA CASTELLANOS JAIR ALBERTO','CRA 12 #5-31 BARRIO SAN RAFAEL','jaircastellanos_0515@hotmail.com','3042197798','68547','0','CO'],
            ['13815429','1','CC','1','1','BUENAHORA LOZA JAIME','CRA 35 #8-45','jaimefac.ele@gmail.com','3135556882','20011','0','CO'],
            ['37555732','1','CC','1','1','CAMACHO VARGAS DARCY YOLIMA','CLL 26 #1-05 BARRIO LA FERIA','yolacama1701@hotmail.com','3212274760','68001','0','CO'],
            ['1094832984','5','CC','1','1','CAMARGO JAIME YORFAN SAID','CRA 2 CL 9 #9C-04 BARRIO EL PORVENIR','yorfanjaiime83@gmail.com','3229087592','54553','0','CO'],
            ['88032951','7','CC','1','1','CAMARGO JEREZ JUAN CARLOS','CALLE 6 #8-23 BARRIO URSUA','jcamargojerez2011@hotmail.com','3112846317','54518','0','CO'],
            ['5692214','6','CC','1','2','CAMPOS GRIMALDO EDGAR ARNULFO','VDA GUAMO PEQUEÑO FINCA VILLA DAYANA','gestiongrupovelasco@gmail.com','3125812145','68547','0','CO'],
            ['91350642','0','CC','1','2','CAMPOS GRIMALDO FAVIO HELIODORO','CRA 21 # 152-30 T 16 APYO 302 URB PARQUE SAN AGUSTIN','yamileop@yahoo.com.ar','3123012921','68276','0','CO'],
            ['901224611','0','NI','2','2','CARBONES JERONIMO S.A.S','AV LIBERTADORES CONJUNTO TORRES DEL PARQUE TORRE D APTO 401','drdaniela63@gmail.com','3219634034','54001','0','CO'],
            ['37271638','5','CC','1','1','CARDENAS CAMPEROS YAJAIRA','CL 1 B #5-32 BARRIO LA MERCED','musicartetienda@hotmail.com','3143931114','54001','0','CO'],
            ['1090482412','7','CC','1','1','CARDENAS CARRILLO JOHN JAIRO','MZ 6 LT 19 BARRIO ASUAVIZ','jhonjairoc1031@gmail.com','3104265886','54261','0','CO'],
            ['91044851','1','CC','1','2','CARDENAS MORENO GIL EDUARDO','VDA SANTA INES-RAMARAL 4 FINCA EL REFLEJO','gilcardenaspollo@hotmail.com','3112007522','68689','0','CO'],
            ['1091654004','8','CC','1','2','CARRASCAL CARRASCAL YEINNY','CL 7 # 14-51 BARRIO MERCADO PUBLICO','trilladorasanroque@gmail.com','3112639493','54498','0','CO'],
            ['1093805002','1','CC','1','1','CARRASCAL MALDONADO CARMEN HAYDEE','AV 1 #0-02 URBANIZACION LIMONAR DEL NORTE','carmecarrasmal@gmail.com','3104366829','54001','0','CO'],
            ['1098656755','5','CC','1','1','CARREÑO ARENAS HECTOR ERASMO','Cra 56 #85-31 Ap 229 TO 8','agroveterinariaelbramador@gmail.com','3118881813','68001','0','CO'],
            ['13175228','0','CC','1','1','CARREÑO CARREÑO EVELIO','VEREDA LAS LIZCAS','eveliocarrenocarreno@gmail.com','3176972416','54498','0','CO'],
            ['1090471890','7','CC','1','1','CARVAJALINO TANIA','CALLE 17 #0-04 BARRIO OSPINA PEREZ','Stephany_0594@hotmail.com','3123621714','54001','0','CO'],
            ['1090485643','5','CC','1','2','CARVAJALINO DURAN JOSE LUIS','CL 14 #0-23','joseluis95lez@gmail.com','3118820625','54001','0','CO'],
            ['13354270','9','CC','1','1','CASTELLANOS VERA RAFAEL ANTONIO','CL 0 #1-75 AP 201 BARRIO LLERAS RESTREPO','construrafa@yahoo.es','3142190315','54001','0','CO'],
            ['49673059','1','CC','1','1','CASTILLA MEJIA TORCOROMA','CRA 24A #5-42 BARRIO VEINTE DE JULIO','avicolaayd@gmail.com','3125027679','54498','0','CO'],
            ['77023217','0','CC','1','1','CASTRO RUEDAS NELSON','VEREDA MATAJIRA FINCA VILLA JULIANA','nelsoncastro1913@gmail.com','3142732431','54520','0','CO'],
            ['1007307810','5','CC','1','1','CELIS ALBA LUZ MARY','CR JUAN FRIO MCP VILLA DEL ROSARIO LT URBANO','maryluzcelis@gmail.com','3215070895','54874','0','CO'],
            ['13199493','1','CC','1','1','CELIS VERGEL CARLOS ARNULFO','KDX 27-2 LA Y FUERA DEL MUNICIPIO DE EL ZULIA','carloscelis2310@gmail.com','3196605747','54261','0','CO'],
            ['1048821038','8','CC','1','1','CETINA SANDOVAL JOSE ALEJANDRO','MZ L LT 4 BARRIO LA FORTALEZA','josecetina1988@gmail.com','3107599629','54001','0','CO'],
            ['901636046','7','NI','2','2','CODORNORT JC SAS','KDX 1 #10 VIA SAN FAUSTINO','codornort.cucuta@gmail.com','3133280687','54001','0','CO'],
            ['901770874','1','NI','2','2','COLCAMPOS S.A.S','VIA ANTIGUA BOCONO CONJUNTO SAMANES DE TRAPICHES CA B 8','colcampossas@gmail.com','3167449648','54874','0','CO'],
            ['1090505148','8','CC','1','1','COLMENARES LINDARTE MARIO GABRIEL','AV 7 A # OB N -10','gatoganadero@hotmail.com','3142997710','54001','0','CO'],
            ['901788065','9','NI','2','2','COMERCIALIZADORA AGROPECUARIA ANDRES S.A.S','CL 33 #9A-40','coagroan@gmail.com','3106076711','54405','0','CO'],
            ['901681126','9','NI','2','2','COMERCIALIZADORA Y GRANJA LA DINASTIA SAS','AV 10 #46-46 CS E-13A','ALEXAJ0216@GMAIL.COM','3205578901','54405','0','CO'],
            ['1090477157','3','CC','1','1','CONDE SILVA EMMA','AV 8 #5-60 BARRIO EL CALLEJON','casadelavicultor@gmail.com','3232841957','54001','0','CO'],
            ['22222222222','2','CC','1','1','CONSUMIDOR FINAL','CRA 33 MODULO C CASA 2 MIRADORES DEL LAGO','distrisofraq@gmail.com','','54498','0','CO'],
            ['37329963','6','CC','1','2','CONTRERAS ALVAREZ BEATRIZ ELENA','CLL 8A #14-31','mao03051993@hotmail.com','3156165847','54498','0','CO'],
            ['27765318','8','CC','1','1','CONTRERAS BOHORQUEZ ELSIDA','CALLE 9 #14-22 BARRIO EL MERCADO','cauvis-38@hotmail.com','3158931983','54498','0','CO'],
            ['13364755','1','CC','1','2','CONTRERAS BOHORQUEZ LIBARDO','CLL 7 # 23-15 BARRIO LLANO ECHAVEZ','libardocontreras39@hotmail.com','3176654222','54498','0','CO'],
            ['88000463','7','CC','1','1','CONTRERAS GAUTA GABRIEL ANTONIO','CRA 5 #7-39 BARRIO EL CENTRO','gabrielantoniocontreras1964@gmail.com','3228239068','54172','0','CO'],
            ['60259575','4','CC','1','1','CONTRERAS HERNANDEZ YANETH','CRA 5 #11-50 BARRIO LA VICTORIA','yanetcontreraz12@gmail.com','3214591695','54001','0','CO'],
            ['91487704','8','CC','1','2','CONTRERAS PIÑERES FERNANDO','CR 25 #20-25 AP 201 ED VALPARAISO','fervetzoo@gmail.com','3185180297','68001','0','CO'],
            ['901730744','1','NI','2','2','CRIADERO LA ESTANCIA S.A.S','KDX 554 FINCA LA ESTANCIA','CRIADEROLAESTANCIA.1@GMAIL.COM','3113217621','54874','0','CO'],
            ['1092394712','1','CC','1','1','CRISPIN VELANDIA CESAR AUGUSTO','VEREDA LAUCHEMA','cesaraugustomc08@gmail.com','3507289490','54874','0','CO'],
            ['1130245120','0','CC','1','1','CUBILLOS SANABRIA ANA MARIA','MZ 2 CA Q5 URBANIZACION QUINTAS DEL TAMARINDO','anamaria208319@hotmail.com','3128797148','54874','0','CO'],
            ['1100968770','1','CC','1','1','CUELLAR ARDILA KEVIN ALEXANDER','FINCA SAN CAYETANO','kevin@hotmail.com','3208593265','68549','0','CO'],
            ['1094321065','9','CC','1','1','DELGADO BAYONA JOSE RAUL','KDX F2 058 BARRIO PRIMERO DE ENERO','raul.delgado.tdh@gmail.com','3134076041','54250','0','CO'],
            ['91040959','1','CC','1','2','DIAZ OTERO MILTON','CLL 11 #12-104 CASCO URBANO','distribucioneselgalpon@gmail.com','3102838071','68689','0','CO'],
            ['901694047','1','NI','2','2','DISTRI ALIMENTOS YJB S.A.S','CRA 14 KDX 143-5 1A CORREGIMIENTO AGUA CLARA','DISYJB@HOTMAIL.COM','3126594335','54001','0','CO'],
            ['901250201','4','NI','2','2','DISTRIALIMENTOS EL MANA SAS','AVE 5 #5-03 LC 1','elmana0119@hotmail.com','3134048239','54001','0','CO'],
            ['901241885','3','NI','2','2','DISTRIBUCIONES DEBRAY S.A.S','CRA 4 #3-127 BARRIO CENTRO','ddebraysas@gmail.com','3156704631','54553','0','CO'],
            ['901441494','5','NI','2','2','DISTRIBUIDORA AGROMAXCOTAS J&G S.A.S','VEREDA LA MUTIS EL HELECHAL','distribuidora.agromaxcotas@gmail.com','3505888458','54874','0','CO'],
            ['901253669','0','NI','2','2','DISTRIBUIDORA AGROPECUARIA GOMEZ MALDONADO S.A.S','CL 5 #11E-04 BARRIO COLSAG','losanimalesdenoesas@gmail.com','3135770929','54001','0','CO'],
            ['901885356','2','NI','2','2','DISTRIBUIDORA AGROPECUARIA GOMEZ S.A.S','CL 6 NRO 6-32','DISTRIBUIDORAGOMEZ25@GMAIL.COM','3213505940','54001','0','CO'],
            ['901264792','6','NI','2','2','DISTRIBUIDORA DE CONCENTRADOS PUENTE TIERRA SAS','KM 08 VIA RIONEGRO-BUCARAMANGA VEREDA SAN IGNACIO','concentradospuentetierra@yahoo.com','3172632948','68615','0','CO'],
            ['890209781','2','NI','2','2','DISTRIBUIDORA LA GRANJA LIMITADA','CR 10 # 43-82','luardiquin63@hotmail.com','6422014','68001','0','CO'],
            ['807007946','1','NI','2','2','DISTRIBUIDORA NUTRIANGO S.A.S','CLL 15 A #4-84 BG 23 BARRIO LA NUEVA SEXTA','gracielaancor@hotmail.es','3144720337','54001','0','CO'],
            ['2478118','1','CC','1','1','DUARTE RODRIGUEZ KELLY ROXANA','MZ 13 C 12 APTO 201 URBANIZACION LA CAMPINA','kelly.keylin2108@gmail.com','3012224356','54405','0','CO'],
            ['27633983','1','CC','1','1','DURAN CAICEDO VERONICA','CRA 4 #2-09 BARRIO LA NAZA','agromigranja2012@gmail.com','3204934658','54099','0','CO'],
            ['5083286','3','CC','1','1','DURAN MENDOZA DAGOBERTO','CRA 14 #8-45 EL MERCADO','ddagoberto88@gmail.com','3168675146','54498','0','CO'],
            ['1090409522','9','CC','1','1','ESTEBAN ROA HUGO RAFAEL','VEREDA SAN ROQUE FINCA EL PESEBRE','hurafa10_06@hotmail.com','3214735091','54720','0','CO'],
            ['13378223','6','CC','1','1','ESTEVEZ ABRIL HERNANDO','CL 57 #68 C-101','SUPERMERCADOBOSQUE1@GMAIL.COM','3206701090','05088','0','CO'],
            ['1090176444','0','CC','1','1','FERNANDEZ ESPINEL FAYL MAURICIO','VDA MCAICEDO LOS ALAMOS K A 2','facturacionfaylmauricio@outlook.com','3106183289','54172','0','CO'],
            ['901375720','2','NI','2','2','FERRE AGRO EL TRIGAL S.A.S','CRA 9 #4- 112 BARRIO LAS DELICIAS','ferreagroeltrigal@hotmail.com','3174591832','54810','0','CO'],
            ['1094167734','8','CC','1','1','FERRER RINCON ALVARO ANDREI','ASOVAI ET 2 LT 6','anferrer0430@gmail.com','3107896367','54261','0','CO'],
            ['37331469','5','CC','1','1','FLOREZ ORTEGA NUBIA','CRA 29 B KDX 411-160 BARRIO EL DORADO','nubiaflorez1977@gmail.com','3158225272','54498','0','CO'],
            ['13277580','7','CC','1','1','FLOREZ VERGARA EDWAR HARVEY','KDX 30 1 CORR LA Y DE ASTILLEROS','malmoga_0426@hotmail.com','3114824971','54261','0','CO'],
            ['52196842','5','CC','1','1','FONSECA GUERRERO CLAUDIA MILENA','CLL 32 #6-02 BARRIO LOS PATIOS','claudifo76@hotmail.com','3204847345','54405','0','CO'],
            ['28345438','5','CC','1','2','FORERO LEON ESMERALDA','CR 17A #53-21','esmeforero@gmail.com','3184014968','68001','0','CO'],
            ['1093792534','8','CC','1','1','GALLEGO GOMEZ ANGIE VALENTINA','AV 7 #35-45 BARRIO LA SABANA','valentinagallegomez@gmail.com','3006374017','54405','0','CO'],
            ['1100896249','5','CC','1','1','GALVIS RICO MARIA LEIDY','TV 14 #11 -35 BARRIO LA PESA','cnafincagro@gmail.com','3212669303','68615','0','CO'],
            ['1102383771','4','CC','1','1','GAMARRA CELIS LUIS DANIEL','CL 19 #30-75','lluisdanielgamarracelis@gmail.com','3203163699','68307','0','CO'],
            ['1090424181','3','CC','1','2','GARCIA CASTELLANOS CYNTHIA DANITSJA','CORR JUAN FRIO MUNICIPIO VILLA ROSARIO','cinthyag610@gmail.com','3138431426','54874','0','CO'],
            ['37317540','2','CC','1','1','GARCIA QUINTANA LIGIA MARIAELENA','CL 7 # 30-149 CA 3 CON EL PRADO','yeinycn@gmail.com','3183215075','54498','0','CO'],
            ['46649152','5','CC','1','1','GIRALDO AIZALES ARACELY','CALLE 13 #3A-31','aracelygiraldo07@gmail.com','3228331280','15572','0','CO'],
            ['63271782','2','CC','1','2','GOMEZ DE RINCON ANA MATILDE','KDX 60 #1A-18 VEREDA JUAN FRIO','lauraacc25@hotmail.com','3138703166','54874','0','CO'],
            ['1126708929','5','CC','1','1','GOMEZ DURAN WILDER ISMAEL','CLL 19 N #16BE-61 BARRIO NIZA','wilderismaelgomez@gmail.com','3203236514','54001','0','CO'],
            ['37749425','7','CC','1','1','GONZALES PEREZ LINA MAYERLI','KM 2 VIA AL ACUEDUCTO CASA 232 VDA LA MALANA','linagonzalesperez.lmgp@gmail.com','3132629712','68001','0','CO'],
            ['1098606823','4','CC','1','1','GONZALEZ GONZALEZ YUDITH','KDX D3 040','angie.gonzalezgo16@gmail.com','3132702951','54250','0','CO'],
            ['91175889','2','CC','1','1','GONZALEZ PRADA JUSTO PASTOR','VEREDA RIOFRIO GRANJA LA FRONTERA SEC ANILLO VIAL','justogonzalez.prada@gmail.com','3183501334','68307','0','CO'],
            ['91176964','1','CC','1','1','GONZALEZ PRADA PEDRO RAMON','DG 21 B #17-101 CONJ SAN JORGE 2 CA F 017 MZ F','pgonzalezprada@gmail.com','3162764099','68307','0','CO'],
            ['13352464','1','CC','1','1','GONZALEZ ROJAS MIGUEL ANGEL','CALLE 33 #9A-40 BARRIO LA SABANA','miguelangelgonzalezrojas60@gmail.com','3106076711','54405','0','CO'],
            ['1193440145','9','CC','1','1','GONZALEZ SANGUINO NICOL ANDREA','Calle 33 #9-90','miguelangelgonzalezrojas60@gmail.com','3106076711','54405','0','CO'],
            ['901346654','0','NI','2','2','GRANJA GERIZIM SAS','VEREDA MATAJIRA FINCA EL PARAISO','granjagerizim@hotmail.com','3112261587','54520','0','CO'],
            ['900589155','6','NI','2','2','GRUPO CAVA AGROPECUARIO S.A.S','CL 29 #31-110 LC 210 ED LEPARC','grupocavaagropecuariosas@hotmail.com','3176526461','68276','0','CO'],
            ['901596354','8','NI','2','2','GRUPO EMPRESARIAL SURTI MASS S.A.S','CL 8 #2-41 BARRIO LA PEDREGOSA','facturacionelectronica@gruposurtimass.com','3183702218','54385','0','CO'],
            ['91292896','5','CC','1','2','GUALDRON MARTINEZ ALDEMAR','BARRIO EL BUQUE CASA 18','agualdronmartinez@gmail.com','3107872729','54518','0','CO'],
            ['27585821','9','CC','1','2','GUERRERO DE FONSECA ROSALBA','CLL 7 #6-70','centralpollitoscucuta@gmail.com','3142947005','54001','0','CO'],
            ['1091662203','0','CC','1','1','GUERRERO NUÑEZ ANDREA','CL 12A #7-35 BARRIO LAS MERCEDES PARTE BAJA','andreaguenuz@gmail.com','3175043560','54498','0','CO'],
            ['1010138590','5','CC','1','1','GUTIERREZ BERNAL YEINI XIOMARA','CL 17E #13B-44 URB PORTAL DE LAS AMERICAS','yeinix0609@gmail.com','3204550082','54001','0','CO'],
            ['77183062','1','CC','1','1','GUTIERREZ ORTEGA JUAN PABLO','KM 8 VIA BUCARAMANGA-RIONEGRO VEREDA SAN IGNACIO FINCA PUENTE TIERRA','concentradospuentetierra@yahoo.com','3176607035','68001','0','CO'],
            ['901597727','6','NI','2','2','HACIENDA LA ESPERANZA NS SAS','VDA LA MUTIS HC LA ESPERANZA','HACIENDALAESPERANZANS@GMAIL.COM','3112771105','54405','0','CO'],
            ['1098656729','3','CC','1','1','HERNANDEZ HERNANDEZ DARWIN FABIAN','VEREDA CERRO DE LA AURORA FINCA NUEVO SOL','didi1700@hotmail.com','3157609770','68406','0','CO'],
            ['1007958270','0','CC','1','1','HERNANDEZ MENDOZA ANGELICA VIVIANA','Avenida 4 #12-32','angelicaviviana151515@gmail.com','3133559576','54001','0','CO'],
            ['70421009','7','CC','1','1','HOYOS GIRALDO JUAN FERNANDO','KDX 28 PA 17 CORR CARMEN DE TONCHALA','hoyosgiraldojuan@gmail.com','3114934692','54001','0','CO'],
            ['1090525959','1','CC','1','1','HURTADO BALLESTEROS MICHELLE JULIETH','KDX 15 2 LOTE 1','granjaaviguadalupe@gmail.com','','54673','0','CO'],
            ['900812434','2','NI','2','2','INVERSION DEL CAMPO S.A.S','CL 9B #10-32','inversiondelcamposas@outlook.es','3185158399','68406','0','CO'],
            ['901813838','2','NI','2','2','INVERSIONES C ESTEVEZ SAS','CR 82D 45 A 03 SUR','inversionesestevez@gmail.com','3204551007','11001','0','CO'],
            ['900408041','1','NI','2','2','INVERSIONES DEL GRANJERO S.A.S','CRA 25 #147-295 CONJ MONTICELLO','inversionesdelgranjerosas@hotmail.com','3188004529','68276','0','CO'],
            ['901233201','2','NI','2','2','INVERSIONES LAMUS PEÑA SAS','CRA 21 #17-39 BARRIO BOLIVAR','sonia_yadira07@hotmail.com','3163983681','68167','0','CO'],
            ['60420727','6','CC','1','1','JAIMES ORTEGA CELINA','VEREDA LA COLORADA','galvisjaimesmariapaola27@gmail.com','3102831881','54172','0','CO'],
            ['72277253','8','CC','1','1','JIMENEZ GUERRERO MARIO ENRIQUE','CL 14 #16-18 BARRIO TOLEDO PLATA','mariotancredo4@gmail.com','322026292','54001','0','CO'],
            ['901865360','7','NI','2','2','LA GRANJA SUMINISTROS S.A.S','CL 6 #6-42','NIURKAALVAREZ07@GMAIL.COM','3144051382','54001','0','CO'],
            ['901983341','2','NI','2','2','LA SABANA AVICOLA SAS','CRA 23 #55-75 ESQUINA BARRIO BOLARQUI','AVICOLALASABANA93@GMAIL.COM','3185712997','68001','0','CO'],
            ['901652841','3','NI','2','2','LADRILLERA SOTO GARCIA SO S.A.S','KDX 21 IB','LADRILLERASOTOGARCIASAS@GMAIL.COM','3156143064','54261','0','CO'],
            ['1095795269','1','CC','1','1','LAMUS ARCINIEGAS DIEGO FERNANDO','TV 93 #34-180 T 3 APTO 1203 BARRIO EL TEJAR','diegolamus2@hotmail.com','3166201721','68001','0','CO'],
            ['37339058','8','CC','1','1','LAZARO LAZARO DIANA','CLL 24 #53-91 BARRIO ANTONIA SANTOS','dianalazaro734@gmail.com','3114616716','54001','0','CO'],
            ['13389883','4','CC','1','2','LEAL HERNANDEZ GUILLERMO','CRA 14 #8-76 BARRIO EL MERCADO','lacasadelgranjero8@gmail.com','3188014471','54498','0','CO'],
            ['88306754','1','CC','1','1','LEGUIZAMON MENDOZA FREDY OMAR','KD 120-4 BARRIO VOLADOR','fredyleguiza83@hotmail.com','3107591722','54820','0','CO'],
            ['37366974','4','CC','1','2','LEMUS ARCINIEGAS AURA DEL CARMEN','CL 14 #0-33','camilo_amaya@hotmail.es','3102613771','54001','0','CO'],
            ['1003167955','8','CC','1','1','LINARES PALOMINO JORGE LUIS','CL 2 #10-58','jpalomino946@gmail.com','3165490220','20517','0','CO'],
            ['60319657','8','CC','1','1','LINDARTE ESTEBAN ELSA ZULAY','AV 7a #0BN-10','gatoganadero@hotmail.com','3142997710','54001','0','CO'],
            ['79275734','5','CC','1','1','LIZCANO CARRILLO LUIS ENRIQUE','CALLE 2 #3-07','LUENLIZ03@YAHOO.ES','3208312791','54223','0','CO'],
            ['1098675754','9','CC','1','2','LOZA BLANCO FREDY ANDRES','CORR JUAN FRIO GRANJA SANTA ROSA','freloza07@hotmail.com','3153729821','54874','0','CO'],
            ['91263804','4','CC','1','1','MACHUCA ABRIL ENRIQUE','CRA 6A #14-36 BARRIO LA CANDELARIA','enriquemachucaabril@gmail.com','3158928102','68547','0','CO'],
            ['13515151','2','CC','1','1','MACIAS DIAZ LEONEL','CL 58 #15- 36 TO 3 APTO 711','leonelmaciasdiaz@hotmail.com','3142709427','68547','0','CO'],
            ['91473541','3','CC','1','2','MALDONADO SUAREZ CESAR AUGUSTO','CR 7A #13-10 LC 202','rionagro@gmail.com','3167418872','68615','0','CO'],
            ['1098723429','6','CC','1','1','MANCIPE GARZON NILSA YERALDY','CALLE 58 #12-104','GERALMANCIPE@GMAIL.COM','3202361153','68276','0','CO'],
            ['1102360196','1','CC','1','1','MANTILLA ARDILA JESUS','CLL 11 B #1D-20 BARRIO ZAFIRO','jesus_89mantilla@hotmail.com','3134312145','68547','0','CO'],
            ['1098736307','2','CC','1','1','MARTINEZ HERRERA LUIS OSNAIDER','CLL 17 #8-31 BARRIO SANTANDER','almacenelcampo@hotmail.com','3115422597','13670','0','CO'],
            ['1007843277','7','CC','1','1','MEJIA GUALDRON SERGIO IVAN','VEREDA CERRO DE LA AURORA','sergiogualdron123@gmail.com','3174754871','68406','0','CO'],
            ['1094532421','3','CC','1','1','MELO MEZA JESUS IVAN','CALLE 5 #4-20 BARRIO CENTRO','jesusivanmelo@gmail.com','3132112280','54418','0','CO'],
            ['27720750','3','CC','1','1','MENDOZA VARGAS MIRYAN ROSALBA','Cll 1 AV 3 # 12-38','Arosalbamendezvargas750@gmail.com','3108075115','54001','0','CO'],
            ['88238113','7','CC','1','2','MENESES GALVAN CARLOS ALBERTO','CL 9AN #12-20','Maxcotalandia@gmail.com','3158260469','54001','0','CO'],
            ['37199333','7','CC','1','1','MEZA SERRANO MARIBEL','KDX 27 LA Y','mary120333@hotmail.com','3208530011','54261','0','CO'],
            ['91440326','4','CC','1','1','MOLINA MORALES LUIS RAFAEL','CL 203 #40-33 LC 13 BARRIO LOS ANDES','luisrafamolina@hotmail.com','3103622499','68276','0','CO'],
            ['41100831','2','CC','1','1','MOLINA VALENCIA MARIA LUZ MERY','','','3112788272','81001','0','CO'],
            ['1005075095','0','CC','1','1','MORA ORTEGA OMAR','AVENIDA 9# 14-20 BARRIO PANAMERICANO','neimarmora59@gmail.com','3145710234','54001','0','CO'],
            ['91451825','5','CC','1','2','MORENO JAIMES HENRY','CLL 4 #4-03','henrymj31@yahoo.es','3114820647','68051','0','CO'],
            ['5671549','8','CC','1','1','MORENO RUEDA RAMON ANTONIO','CRA 22 #10B-15 BARRIO VILLAS DE SAN JUAN','diegocamilomorenoruiz@gmail.com','3118658927','68307','0','CO'],
            ['13492086','0','CC','1','1','MUÑOZ MORENO GERMAN ORLANDO','CLL 11 #20-31 BARRIO CUNDINAMARCA','daniela150297@hotmail.com','3158757699','54001','0','CO'],
            ['88234883','1','CC','1','1','NAVARRO CHACON JUAN CARLOS','CALLE 6N #0-11 ZULIA','danyplus7996@gmail.com','3148184927','54261','0','CO'],
            ['88280166','4','CC','1','1','NAVARRO JAIME ALONSO','CRA 20 A #10A-97 BARRIO EL BAMBO','granjaelrosal.22@gmail.com','','54498','0','CO'],
            ['1091664992','2','CC','1','1','NEIRA ALVAREZ LEONARDO','CALLE 5 #5-22 BARRIO LA MARINA','granjadonrafa@gmail.com','3182264933','20710','0','CO'],
            ['91343560','6','CC','1','1','NIÑO HERNANDEZ SAMUEL','CRA 6 # 12-40 BARRIO CENTRO','samuelnino0227@gmail.com','3134084542','68547','0','CO'],
            ['88226256','1','CC','1','1','NOVOA JESUS','CALLE 16 #0-49 BARRIO MOTILONES','mariluz18santiago@hotmail.com','3125840384','54001','0','CO'],
            ['37345329','3','CC','1','1','OCAMPO GUTIERREZ SANDRA MILENA','MZ T LT 15 BARRIO LA FORTALEZA SEC EL MIRADOR','sandramilenaocampog@gmail.com','3138518613','54001','0','CO'],
            ['700216900','1','CE','1','2','OGANDO PARADA JOSE CARLOS','CL 7A #11E-07 OF 306 CENTRO EMPRESARIAL COLSAG','grupointuria@gmail.com','3114883526','54001','0','CO'],
            ['13862072','6','CC','1','1','OLIVARES CARDENAS HENRY ANTONIO','CL 20N 18E-77 BARRIO NIZA','hencar0616@gmail.com','3016381500','54001','0','CO'],
            ['901300388','8','NI','2','2','ORGANIZACION LEAL SERRANO S.A.S ZESE','CRA 14 #8-76','lacasadelgranjero8@gmail.com','3188014471','54498','0','CO'],
            ['1094577998','4','CC','1','1','ORTEGA LEON YENIRI PAOLA','CRA 11 #6-10 AP 201','paoj2203@hotmail.com','3183077691','54498','0','CO'],
            ['1065900031','8','CC','1','1','ORTEGA SANCHEZ SANDRITH TATIANA','CRA 20 # 51A-16','sandrithortega@hotmail.com','3176644704','68001','0','CO'],
            ['91271816','6','CC','1','1','PABON JUAN CARLOS','FINCA LA BOCATOMA VEREDA BLANQUISTAL','juancarpabon@hotmail.com','3115785531','68547','0','CO'],
            ['88310275','9','CC','1','1','PABON PRADA LUCIANY FRANCISCO','CL 30 #6A-67 BARRIO PATIO CENTRO','facturacionfranciscopabon@hotmail.com','3164156537','54405','0','CO'],
            ['88286347','8','CC','1','1','PACHECO PINEDA JAIRO','KDX 351 240 CL 2 BN 55-37 BARRIO LOS SAUCES','dpachecogomez98@gmail.com','3209910062','54498','0','CO'],
            ['88228062','7','CC','1','1','PALACIOS BOHORQUEZ LUIS EVELIO','CALLE 14 #0-41 BARRIO OSPINA PEREZ','eveliopalacios09@gmail.com','3102379453','54001','0','CO'],
            ['91349665','8','CC','1','1','PALOMINO VIVIESCAS FERNANDO','CLL 35 # 17-77 OF 1005 ED BANCOQUIA','gestiongrupovelasco@gmail.com','3124198648','68001','0','CO'],
            ['1005062770','8','CC','1','1','PARADA BAUTISTA DANIELA JULIETH','CRA 4 #8-168 SEC SAN FRANCISCO','juliparada28@outlook.com','3212934390','54518','0','CO'],
            ['1090374288','7','CC','1','1','PARADA SUAREZ ALBEIRO','CAMPO ALEGRE CORREGIMIENTO AGUA CLARA','albeiroparada9988@gmail.com','3126594335','54001','0','CO'],
            ['5414873','9','CC','1','1','PARADA TOLOSA PASTOR','GRANJA LOS KIOSCOS','erikaadrianaparadabuitrago@gmail.com','3122301856','54520','0','CO'],
            ['1007775653','1','CC','1','1','PARRA CHINCHILLA CRISTIAN DAVID','CORR EL CENTRO VDA LAS MERCEDES','cristian55_parra@hotmail.com','3102471736','68081','0','CO'],
            ['91202202','1','CC','1','2','PEDROZA CORREDOR OCTAVIO','CLL 8 #10-38','Potenay58@hotmail.com','3174236447','68406','0','CO'],
            ['1006594261','7','CC','1','1','PEREZ FLOREZ VICTOR MANUEL','VEREDA LOS SANTOS FINCA LOS GUANABANOS','22victorperez@gmail.com','3177905322','68001','0','CO'],
            ['1144024983','2','CC','1','1','PEREZ FONTECHA JAYVER','VEREDA SAN VICENTE FINCA LA ALCANCIA','jayverp25@gmail.com','3212121950','68861','0','CO'],
            ['86004715','9','CC','1','1','PEREZ ORTIZ JAIME','CLL 11 BN #5-54 BARRIO BOSQUE NAPOLES','petermuleta@hotmail.com','3102866903','54001','0','CO'],
            ['1098631773','1','CC','1','1','PERUCHO GARCES YORLADYS','VEREDA VENADILLO KDX4 FINCA VILLA MARIA','yorly1126@hotmail.com','3134838097','54498','0','CO'],
            ['1005064659','7','CC','1','1','PEÑA SANGUINO FERNANDO JOSE','FINCA CANAAN CORREGIMIENTO URIMACO','fpena7244@gmail.com','3232923736','54673','0','CO'],
            ['13175274','1','CC','1','1','PICON LOPEZ JAVIER MAURICIO','CORREGIMIENTO AGUAS CLARAS CA KDX 160-161','javierpicon1883@gmail.com','3137574067','54498','0','CO'],
            ['13364665','7','CC','1','1','PITTA PEÑARANDA LEONARDO','CRA 14 #8-21','facturaciondistriavicola@gmail.com','3173833009','54498','0','CO'],
            ['901689357','1','NI','2','2','PLASTIAGROS OCAÑA SAS','CA 413-440','PLASTIAGROOCANA@GMAIL.COM','3133187234','54498','0','CO'],
            ['1104184423','1','CC','1','1','PRADA BARRERA ELKIN JULIAN','CL 12 #5-53 BARRIO MONSERRATE','monicaalbiades@gmail.com','3203380206','68547','0','CO'],
            ['13539915','6','CC','1','1','PRADA GOMEZ NELSON DARIO','FINCA EL PLATANAL VEREDA EL VOLADOR','pradanelson95@gmail.com','3138473937','68547','0','CO'],
            ['19132851','7','CC','1','1','PRADO PINZON DUSTANO ALFONSO','VEREDA BAJO GUAMITO','avicolaklo-klo@hotmail.com','3102250753','68077','0','CO'],
            ['890321213','9','NI','2','2','PRODUCTORA NACIONAL AVICOLA S.A.S','MEDIACANOA KM 8 VIA BUGA BUENAVENTURA','info@pronavicola.com','6022374242','76890','0','CO'],
            ['900142547','0','NI','2','2','PROMOTORA DE INNOVACION EN BIOTECNOLOGIA S.A.S','PAR INDUSTRIAL MZ C BG 17 KM 3 VIA PALENQUE CAFE MADRID','asistencia@promitec.com.co','6761686','68001','0','CO'],
            ['901889073','1','NI','2','2','QUIMI-AGRO ALIMENTOS DEL NORTE SAS','KDX 22 VIA LOS PATIOS','QUIMIAGROSAS25@GMAIL.COM','3118129490','54405','0','CO'],
            ['1234339186','8','CC','1','1','QUINTERO MEJIA JONATAN JOSUE','CRA 1 B #29A-15','jonatanquintero2019@gmail.com','3214331602','68276','0','CO'],
            ['1098673073','2','CC','1','1','RAMIREZ VILLACRESES MARIO ANDRES','AV 87 #24-09','MARIORAMIREZVILLACRESES@GMAIL.COM','','68001','0','CO'],
            ['60327410','1','CC','1','1','REYES NUBIA','CLL 15 #0-02','rigonubia3@gmail.com','3138938196','54001','0','CO'],
            ['13514155','7','CC','1','1','RINCON GOMEZ NELSON FRANCISCO','AV 8A CL 6 TRR B APTO 703 ED TORRES DEL ESTE','pacho2478@hotmail.com','3134048279','54001','0','CO'],
            ['1090176976','7','CC','1','1','RINCON JAIMES LIZETH KATHERINE','K-19 BARRIO EL CENTRO CRR LA DONJUANA','licethjaimes28@gmail.com','3209455663','54099','0','CO'],
            ['91514445','1','CC','1','2','RINCON NAVARRO MARIO ROBERTO','CRA 11 #11-12','mariomvz@hotmail.com','3153520028','68689','0','CO'],
            ['1094164973','8','CC','1','1','RINCON RIVERA EDWIN ALONSO','MZ C LT 6 BARRIO ASUAVIZ II ETAPA','ed281194@gmail.com','3164195269','54261','0','CO'],
            ['1093747905','6','CC','1','1','RODRIGUEZ LASSO EDWIN ANDRES','KDX 5 SEC HORACIO OLAVE','edwinrodriguezl@hotmail.com','320947500','54810','0','CO'],
            ['37210262','9','CC','1','1','RODRIGUEZ PEÑA ALBERTANIA','VEREDA CAÑAGUATE','nancy.tqm@hotmail.com','3123483397','54261','0','CO'],
            ['1005290652','4','CC','1','1','RONCANCIO HERRERA ANGEL FERNANDO','CRA 6 #11-03 BARRIO CENTRO','aroncancio2002@gmail.com','3184294850','68547','0','CO'],
            ['1094662449','6','CC','1','1','ROZO CONDE RAFAEL HIPOLITO','CLL 2 #2-51 BARRIO QUINTA REAL','rahiroco26@gmail.com','3204631624','54377','0','CO'],
            ['1094247241','2','CC','1','1','RUBIO CONTRERAS CARLOS HUMBERTO','CRA 3 #5-12 BARRIO SOGAMOSO','lizcanonidiam@gmail.com','3219825384','54223','0','CO'],
            ['91480478','6','CC','1','1','RUEDA DIDIAN FABIAN','CRA 26 #2-65 BARRIO BAHONDO','construaceros@hotmail.com','3153858681','68307','0','CO'],
            ['63518593','1','CC','1','1','RUEDA PINILLA ANA ISABEL','CL 35A #41-39 BARRIO MANDARINOS','contralor@hotelsansilvestre.com','3108697671','68081','0','CO'],
            ['1004897621','9','CC','1','1','RUEDAS ASCANIO BRALLANS EDUARDO','CLL 11 N 14A-69 BARRIO LA ALEJANDRA','maderaselsaman@gmail.com','3226437698','54261','0','CO'],
            ['37860074','9','CC','1','1','RUIZ ALFONZO GINA PAOLA','CRA 40 # 48 -07 AP 503','business.ruiz@gmail.com','3158620600','68001','0','CO'],
            ['60254851','1','CC','1','2','SAAVEDRA CAICEDO MARIA DEL PILAR','CENTRO DE ACOPIO BODEGA 13','mapisa531@hotmail.com','3158527650','54518','0','CO'],
            ['88186381','1','CC','1','1','SALAZAR ARCHILA OMAR','SEC MADRE TIERRA PARCELA VERDOLAGA VIA URIMACO','omararchus@outlook.com','3214204544','54001','0','CO'],
            ['91176631','4','CC','1','1','SANABRIA VEGA JOSE DEL CARMEN','CALLE 10B #24-09 LT 8 MZ F BARRIO VILLAS DE SAN JUAN','josesanabriavega65@gmail.com','3156378708','68307','0','CO'],
            ['60289029','2','CC','1','1','SANCHEZ ARAQUE MARTHA','AVE 20 #15-01 BARRIO SAN JOSE','elizabethortegasanchez1395@gmail.com','3114399062','54001','0','CO'],
            ['1091670487','9','CC','1','1','SANCHEZ FLOREZ ELKIN NORIEL','CR 14 7 42','oasis3312@hotmail.com','3058052100','54498','0','CO'],
            ['1065237450','7','CC','1','1','SANCHEZ JULIO YULEYDY','CORREGIMIENTO EL CARAÑO','sanchesyuleidis132@gmail.com','3174440110','54385','0','CO'],
            ['1092356456','9','CC','1','1','SANCHEZ VERGEL JOSE GREGORIO','CRA 7 #7-06 BARRIO EL CENTRO','josesanty029@gmail.com','3165669324','54874','0','CO'],
            ['9691837','4','CC','1','1','SEPULVEDA JOSE ALFREDO','CALLE 4 #15-89','josealfredosepulveda82@hotmail.com','3172764261','20011','0','CO'],
            ['37815395','7','CC','1','1','SERRANO DE GUTIERREZ BEATRIZ','CALLE 7 #8-43','','3134048239','54874','0','CO'],
            ['1090512416','6','CC','1','1','SILVA GUERRERO JAVIER ANDRES','CON GRATAMIRA BL 1 AP 202','javiersilva198@gmail.com','3112164823','54001','0','CO'],
            ['91112503','5','CC','1','2','SILVA PARRA ANDRES OCTAVIO','VEREDA EL GUAMO FINCA EL LAGO','wandolp@hotmail.com','3178226825','68547','0','CO'],
            ['1102381431','6','CC','1','1','SILVA ROJAS ANDRES FABIAN','FINCA LA CONSUELO VEREDA GUAMO PEQUEÑO','elpasesores.1@gmail.com','3126894246','68547','0','CO'],
            ['1064839193','9','CC','1','1','SOLANO BAYONA SAID ANTONIO','KDX 413 420 BARRIO EL LIBANO','plastiagroocana@gmail.com','3133187234','54498','0','CO'],
            ['49664737','7','CC','1','1','SOLANO CONTRERAS HAIDE','VEREDA BUENAVISTA','Haideesolanocontreras@gmail.com','3208308937','54498','0','CO'],
            ['91468694','1','CC','1','1','SUAREZ BERMUDEZ URIEL','CRA 4 #53-54 BARRIO BALCONCITOS DEL MUTIS','facturacionurielsuarez@gmail.com','3178730409','68001','0','CO'],
            ['27789860','2','CC','1','2','SUAREZ DE RICO MIRYAM AMALIA','TV 14 #11-35','fincagro74@gmail.com','3182009564','68615','0','CO'],
            ['901182183','8','NI','2','2','SURTIGRANJEROS S.A.S ZOMAC','CR 7 #5-18','surtigran1974@hotmail.com','3133176195','54720','0','CO'],
            ['91516724','0','CC','1','1','TARAZONA LEON FABIAN ERNESTO','CL 3 AN #1A-03 BARRIO EL REFUGIO','fabiantarazona@hotmail.com','6552618','68547','0','CO'],
            ['900297286','9','NI','2','2','TECNOLOGIAS EN NUTRICION ORGANICA S.A NORGTECH S.A','CLL 27 #5-19','norgtech@gmail.com','3186297786','68001','0','CO'],
            ['1005322567','5','CC','1','1','TOLOZA CARRILLO LAURA VALENTINA','FINCA VILLA DE LEYVA VEREDA MIRABEL','lauravtc24@gmail.com','3183265085','68406','0','CO'],
            ['13537281','6','CC','1','1','TOLOZA URIBE HERNAN','FINCA VILLA DE LEYVA KM 26 VIA BARRANCA','tolozahernan956@gmail.com','3156274832','68406','0','CO'],
            ['88202752','8','CC','1','1','TORO LEAL LUIS ARMANDO','AV 0A #5-31 BARRIO BOGOTA','luisarmandotoroleal@gmail.com','3105538802','54001','0','CO'],
            ['13177131','4','CC','1','1','TORRADO ASCANIO HECTOR STEEVENSON','CALLE 4 #19A-42','','3184873584','54498','0','CO'],
            ['1094579869','1','CC','1','1','TRIGOS LOPEZ BRAYAN ANDERSON','CA KDX 195 -437 EL ALGODONAL','brayantrigos32@gmail.com','3125509818','54498','0','CO'],
            ['1090512222','4','CC','1','1','TRUJILLO HERNANDEZ JOSE REINALDO','VEREDA AGUALINDA','josrey1997@hotmail.com','3122115160','54250','0','CO'],
            ['37619810','2','CC','1','1','VANEGAS SUAREZ FANNY','FINCA ALTO VIENTO','wendyjulianagarcia952@gmail.com','3105304368','68547','0','CO'],
            ['1090399700','9','CC','1','1','VEGA BOHORQUEZ ALBEIRO','MZ H LT 3 BARRIO MINUTOS DE DIOS','aldian123@hotmail.com','3208271136','54001','0','CO'],
            ['13412063','1','CC','1','1','VEGA GELVES DIONICIO','CL 3 #3-47 KDX 73 BARRIO LA PRESENTACION','dionicio1974@hotmail.com','3108062515','54051','0','CO'],
            ['1091661225','8','CC','1','1','VEGA PEREZ RUBIELA','CALLE 7 #14-09 MERCADO PUBLICO OCAÑA','rvegap89@gmail.com','3118003983','54498','0','CO'],
            ['63478658','7','CC','1','1','VELANDIA ALVAREZ LUDIVIA','URB SAN JORGE CASA 40','electrolujosgiron@hotmail.com','3182062148','68307','0','CO'],
            ['91439953','0','CC','1','1','VELASQUEZ ARIAS LUIS HERMES','VDA GUARIGUA','lhvelasquez_04@hotmail.com','3206679020','68679','0','CO'],
            ['1098770789','2','CC','1','1','VELASQUEZ LOPEZ ANGIE KATHERINE','CRA 37 B #52-77 BARRIO EL TRIUNFO','angielopez.1306@gmail.com','3173326495','68081','0','CO'],
            ['88244853','3','CC','1','1','VELEZ RODRIGUEZ MANUEL ALEJANDRO','CLL 1 AN #14E-35 CA 67 URBANIZACION PRADOS II','manuelvelezr2406@gmail.com','3133513246','54001','0','CO'],
            ['1092154742','3','NI','1','1','VELOZA JIMENEZ EMEL ALFONSO','CL 24 #54-24 LC 1 BARRIO ANTONIA SANTOS','evelozajimenez@gmail.com','3202375908','54001','0','CO'],
            ['1090175325','8','CC','1','1','VERA RAMIREZ GILMA YULEY','CALLE 3 #5-44 BARRIO EL CARMEN','giyuver25@hotmail.com','3187543996','54172','0','CO'],
            ['1102387163','4','CC','1','1','VERA SEQUEDA LUIS ERNESTO','CL 16 #3WA-11 BARRIO PORTAL DE BELEN','luis.s.vera98@gmail.com','3184745407','68547','0','CO'],
            ['28338488','4','CC','1','1','VILLAMIZAR GONZALEZ LUZ AMPARO','FCA EL DIAMANTE CORR 2 VEREDA LOS SANTOS','luzamparovillamizar480@gmail.com','3023053057','68001','0','CO'],
            ['60258756','6','CC','1','1','VILLAMIZAR PORTILLA CARMEN OLIVA','CRA 5 #7-08 BARRIO CENTRO','lapradera2019@gmail.com','3176871968','54518','0','CO'],
            ['1004818256','6','CC','1','1','VILLAMIZAR SANTIAGO YERSON DARIO','MZ AY PARCELA 1','YERSONVILLAMIZAR91@GMAIL.COM','3209610406','54001','0','CO'],
            ['1050456495','2','CC','1','1','VILLAREAL PASTRANA ROBERTO JAVIER','CALLE 3 #5-44 BARRIO EL CARMEN','villy0586@hotmail.com','3024630034','54172','0','CO'],
            ['37327566','6','CC','1','2','ZAMBRANO QUINTERO ANA YIVE','CLL 14 #7-132','cp.anayivezambrano@hotmail.com','3158761991','54001','0','CO'],
            ['1098790003','8','CC','1','1','ZAMBRANO SALAZAR NATALIA','CL 5 #1-28','nataliazam947@gmail.com','3138632212','68575','0','CO'],
        ];

        $insertados = 0;
        $omitidos   = 0;

        foreach ($clientes as $c) {
            [$id_num, $dv, $doc, $org, $reg, $nombre, $dir, $email, $tel, $dane, $plazo, $pais] = $c;

            $docCodigo = $tipdoc[$doc] ?? '13';
            $orgCodigo = $tipoOrg[$org] ?? '2';
            $regCodigo = $tipoRegimen[$reg] ?? '49';

            // Saltar CONSUMIDOR FINAL (número inválido)
            if ($id_num === '22222222222') {
                $omitidos++;
                continue;
            }

            // Email único o nulo
            $emailFinal = !empty(trim($email)) ? strtolower(trim($email)) : null;

            // Si email duplicado, hacerlo único con sufijo
            if ($emailFinal) {
                $existe = DB::table('customers')->where('email', $emailFinal)->exists();
                if ($existe) {
                    $emailFinal = $id_num . '_' . $emailFinal;
                }
            }

            $existe = DB::table('customers')
                ->where('identification_number', $id_num)
                ->where('type_document_id', $docCodigo)
                ->exists();

            if ($existe) {
                $omitidos++;
                continue;
            }

            try {
                DB::table('customers')->insert([
                    'name'                  => strtoupper($nombre),
                    'identification_number' => $id_num,
                    'dv'                    => $dv ?: null,
                    'type_document_id'      => $docCodigo,
                    'type_organization_id'  => $orgCodigo,
                    'type_regime_id'        => $regCodigo,
                    'type_liability_id'     => 'R-99-PN',
                    'municipality_id'       => $dane,
                    'address'               => strtoupper($dir) ?: 'SIN DIRECCION',
                    'email'                 => $emailFinal,
                    'phone'                 => $tel ?: null,
                    'is_active'             => 1,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
                $insertados++;
            } catch (\Exception $e) {
                $omitidos++;
                \Log::warning("Cliente omitido [{$id_num}]: " . $e->getMessage());
            }
        }

        $this->command->info("Migración completada: {$insertados} insertados, {$omitidos} omitidos.");
    }
}
