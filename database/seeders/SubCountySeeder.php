<?php

namespace Database\Seeders;

use App\Models\SubCounty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCountySeeder extends Seeder
{
    public function run(): void
    {
        $subCounties = [
            // MOMBASA (County ID: 1)
            ['county_id' => 1, 'code' => '00101', 'name' => 'Changamwe'],
            ['county_id' => 1, 'code' => '00102', 'name' => 'Jomvu'],
            ['county_id' => 1, 'code' => '00103', 'name' => 'Kisauni'],
            ['county_id' => 1, 'code' => '00104', 'name' => 'Nyali'],
            ['county_id' => 1, 'code' => '00105', 'name' => 'Likoni'],
            ['county_id' => 1, 'code' => '00106', 'name' => 'Mvita'],

            // KWALE (County ID: 2)
            ['county_id' => 2, 'code' => '00201', 'name' => 'Msambweni'],
            ['county_id' => 2, 'code' => '00202', 'name' => 'Lunga Lunga'],
            ['county_id' => 2, 'code' => '00203', 'name' => 'Matuga'],
            ['county_id' => 2, 'code' => '00204', 'name' => 'Kinango'],

            // KILIFI (County ID: 3)
            ['county_id' => 3, 'code' => '00301', 'name' => 'Kilifi North'],
            ['county_id' => 3, 'code' => '00302', 'name' => 'Kilifi South'],
            ['county_id' => 3, 'code' => '00303', 'name' => 'Kaloleni'],
            ['county_id' => 3, 'code' => '00304', 'name' => 'Rabai'],
            ['county_id' => 3, 'code' => '00305', 'name' => 'Ganze'],
            ['county_id' => 3, 'code' => '00306', 'name' => 'Malindi'],
            ['county_id' => 3, 'code' => '00307', 'name' => 'Magarini'],

            // TANA RIVER (County ID: 4)
            ['county_id' => 4, 'code' => '00401', 'name' => 'Garsen'],
            ['county_id' => 4, 'code' => '00402', 'name' => 'Galole'],
            ['county_id' => 4, 'code' => '00403', 'name' => 'Bura'],

            // LAMU (County ID: 5)
            ['county_id' => 5, 'code' => '00501', 'name' => 'Lamu East'],
            ['county_id' => 5, 'code' => '00502', 'name' => 'Lamu West'],

            // TAITA TAVETA (County ID: 6)
            ['county_id' => 6, 'code' => '00601', 'name' => 'Taveta'],
            ['county_id' => 6, 'code' => '00602', 'name' => 'Wundanyi'],
            ['county_id' => 6, 'code' => '00603', 'name' => 'Mwatate'],
            ['county_id' => 6, 'code' => '00604', 'name' => 'Voi'],

            // GARISSA (County ID: 7)
            ['county_id' => 7, 'code' => '00701', 'name' => 'Garissa Township'],
            ['county_id' => 7, 'code' => '00702', 'name' => 'Balambala'],
            ['county_id' => 7, 'code' => '00703', 'name' => 'Lagdera'],
            ['county_id' => 7, 'code' => '00704', 'name' => 'Dadaab'],
            ['county_id' => 7, 'code' => '00705', 'name' => 'Fafi'],
            ['county_id' => 7, 'code' => '00706', 'name' => 'Ijara'],
            ['county_id' => 7, 'code' => '00707', 'name' => 'Hulugho'],

            // WAJIR (County ID: 8)
            ['county_id' => 8, 'code' => '00801', 'name' => 'Wajir North'],
            ['county_id' => 8, 'code' => '00802', 'name' => 'Wajir East'],
            ['county_id' => 8, 'code' => '00803', 'name' => 'Tarbaj'],
            ['county_id' => 8, 'code' => '00804', 'name' => 'Wajir West'],
            ['county_id' => 8, 'code' => '00805', 'name' => 'Eldas'],
            ['county_id' => 8, 'code' => '00806', 'name' => 'Wajir South'],

            // MANDERA (County ID: 9)
            ['county_id' => 9, 'code' => '00901', 'name' => 'Mandera West'],
            ['county_id' => 9, 'code' => '00902', 'name' => 'Banissa'],
            ['county_id' => 9, 'code' => '00903', 'name' => 'Mandera North'],
            ['county_id' => 9, 'code' => '00904', 'name' => 'Mandera South'],
            ['county_id' => 9, 'code' => '00905', 'name' => 'Mandera East'],
            ['county_id' => 9, 'code' => '00906', 'name' => 'Lafey'],

            // MARSABIT (County ID: 10)
            ['county_id' => 10, 'code' => '01001', 'name' => 'Moyale'],
            ['county_id' => 10, 'code' => '01002', 'name' => 'North Horr'],
            ['county_id' => 10, 'code' => '01003', 'name' => 'Saku'],
            ['county_id' => 10, 'code' => '01004', 'name' => 'Laisamis'],

            // ISIOLO (County ID: 11)
            ['county_id' => 11, 'code' => '01101', 'name' => 'Isiolo'],
            ['county_id' => 11, 'code' => '01102', 'name' => 'Merti'],
            ['county_id' => 11, 'code' => '01103', 'name' => 'Garbatulla'],

            // MERU (County ID: 12)
            ['county_id' => 12, 'code' => '01201', 'name' => 'Igembe South'],
            ['county_id' => 12, 'code' => '01202', 'name' => 'Igembe Central'],
            ['county_id' => 12, 'code' => '01203', 'name' => 'Igembe North'],
            ['county_id' => 12, 'code' => '01204', 'name' => 'Tigania West'],
            ['county_id' => 12, 'code' => '01205', 'name' => 'Tigania East'],
            ['county_id' => 12, 'code' => '01206', 'name' => 'Buuri'],
            ['county_id' => 12, 'code' => '01207', 'name' => 'Imenti Central'],
            ['county_id' => 12, 'code' => '01208', 'name' => 'Imenti South'],
            ['county_id' => 12, 'code' => '01209', 'name' => 'Imenti North'],

            // THARAKA-NITHI (County ID: 13)
            ['county_id' => 13, 'code' => '01301', 'name' => 'Maara'],
            ['county_id' => 13, 'code' => '01302', 'name' => 'Chuka'],
            ['county_id' => 13, 'code' => '01303', 'name' => 'Tharaka North'],
            ['county_id' => 13, 'code' => '01304', 'name' => 'Tharaka South'],
            ['county_id' => 13, 'code' => '01305', 'name' => 'Chiakariga and Muthambi'],
            ['county_id' => 13, 'code' => '01306', 'name' => 'Igambang\'ombe'],

            // EMBU (County ID: 14)
            ['county_id' => 14, 'code' => '01401', 'name' => 'Manyatta'],
            ['county_id' => 14, 'code' => '01402', 'name' => 'Runyenjes'],
            ['county_id' => 14, 'code' => '01403', 'name' => 'Mbeere South'],
            ['county_id' => 14, 'code' => '01404', 'name' => 'Mbeere North'],

            // KITUI (County ID: 15)
            ['county_id' => 15, 'code' => '01501', 'name' => 'Mwingi North'],
            ['county_id' => 15, 'code' => '01502', 'name' => 'Mwingi West'],
            ['county_id' => 15, 'code' => '01503', 'name' => 'Mwingi Central'],
            ['county_id' => 15, 'code' => '01504', 'name' => 'Kitui West'],
            ['county_id' => 15, 'code' => '01505', 'name' => 'Kitui Rural'],
            ['county_id' => 15, 'code' => '01506', 'name' => 'Kitui Central'],
            ['county_id' => 15, 'code' => '01507', 'name' => 'Kitui East'],
            ['county_id' => 15, 'code' => '01508', 'name' => 'Kitui South'],

            // MACHAKOS (County ID: 16)
            ['county_id' => 16, 'code' => '01601', 'name' => 'Masinga'],
            ['county_id' => 16, 'code' => '01602', 'name' => 'Yatta'],
            ['county_id' => 16, 'code' => '01603', 'name' => 'Matungulu'],
            ['county_id' => 16, 'code' => '01604', 'name' => 'Kathiani'],
            ['county_id' => 16, 'code' => '01605', 'name' => 'Mavoko'],
            ['county_id' => 16, 'code' => '01606', 'name' => 'Machakos Town'],
            ['county_id' => 16, 'code' => '01607', 'name' => 'Mwala'],

            // MAKUENI (County ID: 17)
            ['county_id' => 17, 'code' => '01701', 'name' => 'Mbooni'],
            ['county_id' => 17, 'code' => '01702', 'name' => 'Kilome'],
            ['county_id' => 17, 'code' => '01703', 'name' => 'Kaiti'],
            ['county_id' => 17, 'code' => '01704', 'name' => 'Makueni'],
            ['county_id' => 17, 'code' => '01705', 'name' => 'Kibwezi West'],
            ['county_id' => 17, 'code' => '01706', 'name' => 'Kibwezi East'],

            // NYANDARUA (County ID: 18)
            ['county_id' => 18, 'code' => '01801', 'name' => 'Kinangop'],
            ['county_id' => 18, 'code' => '01802', 'name' => 'Kipipiri'],
            ['county_id' => 18, 'code' => '01803', 'name' => 'Ol Kalou'],
            ['county_id' => 18, 'code' => '01804', 'name' => 'Ol Joro Orok'],
            ['county_id' => 18, 'code' => '01805', 'name' => 'Ndaragwa'],

            // NYERI (County ID: 19)
            ['county_id' => 19, 'code' => '01901', 'name' => 'Tetu'],
            ['county_id' => 19, 'code' => '01902', 'name' => 'Kieni East'],
            ['county_id' => 19, 'code' => '01903', 'name' => 'Kieni West'],
            ['county_id' => 19, 'code' => '01904', 'name' => 'Mathira East'],
            ['county_id' => 19, 'code' => '01905', 'name' => 'Mathira West'],
            ['county_id' => 19, 'code' => '01906', 'name' => 'Othaya'],
            ['county_id' => 19, 'code' => '01907', 'name' => 'Mukurweini'],
            ['county_id' => 19, 'code' => '01908', 'name' => 'Nyeri Town'],

            // KIRINYAGA (County ID: 20)
            ['county_id' => 20, 'code' => '02001', 'name' => 'Mwea East'],
            ['county_id' => 20, 'code' => '02002', 'name' => 'Mwea West'],
            ['county_id' => 20, 'code' => '02003', 'name' => 'Kirinyaga Central'],
            ['county_id' => 20, 'code' => '02004', 'name' => 'Kirinyaga East'],
            ['county_id' => 20, 'code' => '02005', 'name' => 'Kirinyaga West'],

            // MURANG'A (County ID: 21)
            ['county_id' => 21, 'code' => '02101', 'name' => 'Kangema'],
            ['county_id' => 21, 'code' => '02102', 'name' => 'Mathioya'],
            ['county_id' => 21, 'code' => '02103', 'name' => 'Kiharu'],
            ['county_id' => 21, 'code' => '02104', 'name' => 'Kigumo'],
            ['county_id' => 21, 'code' => '02105', 'name' => 'Maragwa'],
            ['county_id' => 21, 'code' => '02106', 'name' => 'Kandara'],
            ['county_id' => 21, 'code' => '02107', 'name' => 'Gatanga'],
            ['county_id' => 21, 'code' => '02108', 'name' => 'Kahuro'],
            ['county_id' => 21, 'code' => '02109', 'name' => 'Murang\'a South'],

            // KIAMBU (County ID: 22)
            ['county_id' => 22, 'code' => '02201', 'name' => 'Gatundu South'],
            ['county_id' => 22, 'code' => '02202', 'name' => 'Gatundu North'],
            ['county_id' => 22, 'code' => '02203', 'name' => 'Juja'],
            ['county_id' => 22, 'code' => '02204', 'name' => 'Thika Town'],
            ['county_id' => 22, 'code' => '02205', 'name' => 'Ruiru'],
            ['county_id' => 22, 'code' => '02206', 'name' => 'Githunguri'],
            ['county_id' => 22, 'code' => '02207', 'name' => 'Kiambu Town'],
            ['county_id' => 22, 'code' => '02208', 'name' => 'Kiambaa'],
            ['county_id' => 22, 'code' => '02209', 'name' => 'Kabete'],
            ['county_id' => 22, 'code' => '02210', 'name' => 'Kikuyu'],
            ['county_id' => 22, 'code' => '02211', 'name' => 'Limuru Town'],
            ['county_id' => 22, 'code' => '02212', 'name' => 'Lari'],

            // TURKANA (County ID: 23)
            ['county_id' => 23, 'code' => '02301', 'name' => 'Turkana North'],
            ['county_id' => 23, 'code' => '02302', 'name' => 'Turkana Central'],
            ['county_id' => 23, 'code' => '02303', 'name' => 'Loima'],
            ['county_id' => 23, 'code' => '02304', 'name' => 'Turkana South'],
            ['county_id' => 23, 'code' => '02305', 'name' => 'Turkana East'],

            // WEST POKOT (County ID: 24)
            ['county_id' => 24, 'code' => '02401', 'name' => 'West Pokot'],
            ['county_id' => 24, 'code' => '02402', 'name' => 'South Pokot'],
            ['county_id' => 24, 'code' => '02403', 'name' => 'North Pokot'],
            ['county_id' => 24, 'code' => '02404', 'name' => 'Cenral Pokot'],

            // SAMBURU (County ID: 25)
            ['county_id' => 25, 'code' => '02501', 'name' => 'Samburu West'],
            ['county_id' => 25, 'code' => '02502', 'name' => 'Samburu North'],
            ['county_id' => 25, 'code' => '02503', 'name' => 'Samburu East'],

            // TRANS NZOIA (County ID: 26)
            ['county_id' => 26, 'code' => '02601', 'name' => 'Kwanza'],
            ['county_id' => 26, 'code' => '02602', 'name' => 'Endebess'],
            ['county_id' => 26, 'code' => '02603', 'name' => 'Saboti'],
            ['county_id' => 26, 'code' => '02604', 'name' => 'Kiminini'],
            ['county_id' => 26, 'code' => '02605', 'name' => 'Cherangany'],

            // UASIN GISHU (County ID: 27)
            ['county_id' => 27, 'code' => '02701', 'name' => 'Soy'],
            ['county_id' => 27, 'code' => '02702', 'name' => 'Turbo'],
            ['county_id' => 27, 'code' => '02703', 'name' => 'Moiben'],
            ['county_id' => 27, 'code' => '02704', 'name' => 'Ainabkoi'],
            ['county_id' => 27, 'code' => '02705', 'name' => 'Kapseret'],
            ['county_id' => 27, 'code' => '02706', 'name' => 'Kesses'],

            // ELGEYO/MARAKWET (County ID: 28)
            ['county_id' => 28, 'code' => '02801', 'name' => 'Marakwet East'],
            ['county_id' => 28, 'code' => '02802', 'name' => 'Marakwet West'],
            ['county_id' => 28, 'code' => '02803', 'name' => 'Keiyo North'],
            ['county_id' => 28, 'code' => '02804', 'name' => 'Keiyo South'],

            // NANDI (County ID: 29)
            ['county_id' => 29, 'code' => '02901', 'name' => 'Tinderet'],
            ['county_id' => 29, 'code' => '02902', 'name' => 'Aldai'],
            ['county_id' => 29, 'code' => '02903', 'name' => 'Nandi Hills'],
            ['county_id' => 29, 'code' => '02904', 'name' => 'Chesumei'],
            ['county_id' => 29, 'code' => '02905', 'name' => 'Emgwen'],
            ['county_id' => 29, 'code' => '02906', 'name' => 'Mosop'],

            // BARINGO (County ID: 30)
            ['county_id' => 30, 'code' => '03001', 'name' => 'Tiaty'],
            ['county_id' => 30, 'code' => '03002', 'name' => 'Baringo North'],
            ['county_id' => 30, 'code' => '03003', 'name' => 'Baringo Central'],
            ['county_id' => 30, 'code' => '03004', 'name' => 'Baringo South'],
            ['county_id' => 30, 'code' => '03005', 'name' => 'Mogotio'],
            ['county_id' => 30, 'code' => '03006', 'name' => 'Eldama Ravine'],

            // LAIKIPIA (County ID: 31)
            ['county_id' => 31, 'code' => '03101', 'name' => 'Laikipia West'],
            ['county_id' => 31, 'code' => '03102', 'name' => 'Laikipia East'],
            ['county_id' => 31, 'code' => '03103', 'name' => 'Laikipia North'],
            ['county_id' => 31, 'code' => '03104', 'name' => 'Laikipia Central'],
            ['county_id' => 31, 'code' => '03105', 'name' => 'Nyahururu'],

            // NAKURU (County ID: 32)
            ['county_id' => 32, 'code' => '03201', 'name' => 'Nakuru Town East'],
            ['county_id' => 32, 'code' => '03202', 'name' => 'Nakuru Town West'],
            ['county_id' => 32, 'code' => '03203', 'name' => 'Naivasha'],
            ['county_id' => 32, 'code' => '03204', 'name' => 'Gilgil'],
            ['county_id' => 32, 'code' => '03205', 'name' => 'Kuresoi North'],
            ['county_id' => 32, 'code' => '03206', 'name' => 'Kuresoi South'],
            ['county_id' => 32, 'code' => '03207', 'name' => 'Molo'],
            ['county_id' => 32, 'code' => '03208', 'name' => 'Rongai'],
            ['county_id' => 32, 'code' => '03209', 'name' => 'Bahati'],
            ['county_id' => 32, 'code' => '03210', 'name' => 'Subukia'],
            ['county_id' => 32, 'code' => '03211', 'name' => 'Njoro'],

            // NAROK (County ID: 33)
            ['county_id' => 33, 'code' => '03301', 'name' => 'Narok North'],
            ['county_id' => 33, 'code' => '03302', 'name' => 'Narok East'],
            ['county_id' => 33, 'code' => '03303', 'name' => 'Narok South'],
            ['county_id' => 33, 'code' => '03304', 'name' => 'Narok West'],
            ['county_id' => 33, 'code' => '03305', 'name' => 'Transmara East'],
            ['county_id' => 33, 'code' => '03306', 'name' => 'Transmara West'],

            // KAJIADO (County ID: 34)
            ['county_id' => 34, 'code' => '03401', 'name' => 'Kajiado North'],
            ['county_id' => 34, 'code' => '03402', 'name' => 'Kajiado Central'],
            ['county_id' => 34, 'code' => '03403', 'name' => 'Isinya'],
            ['county_id' => 34, 'code' => '03404', 'name' => 'Mashuuru'],
            ['county_id' => 34, 'code' => '03405', 'name' => 'Loitoktok'],

            // KERICHO (County ID: 35)
            ['county_id' => 35, 'code' => '03501', 'name' => 'Kipkelion East'],
            ['county_id' => 35, 'code' => '03502', 'name' => 'Kipkelion West'],
            ['county_id' => 35, 'code' => '03503', 'name' => 'Ainamoi'],
            ['county_id' => 35, 'code' => '03504', 'name' => 'Bureti'],
            ['county_id' => 35, 'code' => '03505', 'name' => 'Belgut'],
            ['county_id' => 35, 'code' => '03506', 'name' => 'Sigowet/Soin'],

            // BOMET (County ID: 36)
            ['county_id' => 36, 'code' => '03601', 'name' => 'Sotik'],
            ['county_id' => 36, 'code' => '03602', 'name' => 'Chepalungu'],
            ['county_id' => 36, 'code' => '03603', 'name' => 'Bomet East'],
            ['county_id' => 36, 'code' => '03604', 'name' => 'Bomet Central'],
            ['county_id' => 36, 'code' => '03605', 'name' => 'Konoin'],

            // KAKAMEGA (County ID: 37)
            ['county_id' => 37, 'code' => '03701', 'name' => 'Lurambi'],
            ['county_id' => 37, 'code' => '03702', 'name' => 'Navakholo'],
            ['county_id' => 37, 'code' => '03703', 'name' => 'Mumias'],
            ['county_id' => 37, 'code' => '03704', 'name' => 'Butere'],
            ['county_id' => 37, 'code' => '03705', 'name' => 'Khwisero'],
            ['county_id' => 37, 'code' => '03706', 'name' => 'Lugari'],
            ['county_id' => 37, 'code' => '03707', 'name' => 'Kakamega Central'],
            ['county_id' => 37, 'code' => '03708', 'name' => 'Kakamega North'],
            ['county_id' => 37, 'code' => '03709', 'name' => 'Kakamega South'],
            ['county_id' => 37, 'code' => '03710', 'name' => 'Kakamega East'],
            ['county_id' => 37, 'code' => '03711', 'name' => 'Lukuyani'],
            ['county_id' => 37, 'code' => '03712', 'name' => 'Matete'],
            ['county_id' => 37, 'code' => '03713', 'name' => 'Matungu'],

            // VIHIGA (County ID: 38)
            ['county_id' => 38, 'code' => '03801', 'name' => 'Vihiga'],
            ['county_id' => 38, 'code' => '03802', 'name' => 'Sabatia'],
            ['county_id' => 38, 'code' => '03803', 'name' => 'Hamisi'],
            ['county_id' => 38, 'code' => '03804', 'name' => 'Luanda'],
            ['county_id' => 38, 'code' => '03805', 'name' => 'Emuhaya'],

            // BUNGOMA (County ID: 39)
            ['county_id' => 39, 'code' => '03901', 'name' => 'Mt. Elgon'],
            ['county_id' => 39, 'code' => '03902', 'name' => 'Sirisia'],
            ['county_id' => 39, 'code' => '03903', 'name' => 'Kabuchai'],
            ['county_id' => 39, 'code' => '03904', 'name' => 'Bumula'],
            ['county_id' => 39, 'code' => '03905', 'name' => 'Kanduyi'],
            ['county_id' => 39, 'code' => '03906', 'name' => 'Webuye East'],
            ['county_id' => 39, 'code' => '03907', 'name' => 'Webuye West'],
            ['county_id' => 39, 'code' => '03908', 'name' => 'Kimilili'],
            ['county_id' => 39, 'code' => '03909', 'name' => 'Tongaren'],

            // BUSIA (County ID: 40)
            ['county_id' => 40, 'code' => '04001', 'name' => 'Teso North'],
            ['county_id' => 40, 'code' => '04002', 'name' => 'Teso South'],
            ['county_id' => 40, 'code' => '04003', 'name' => 'Nambale'],
            ['county_id' => 40, 'code' => '04004', 'name' => 'Butula'],
            ['county_id' => 40, 'code' => '04005', 'name' => 'Funyula'],
            ['county_id' => 40, 'code' => '04006', 'name' => 'Budalangi'],
            ['county_id' => 40, 'code' => '04007', 'name' => 'Matayos'],

            // SIAYA (County ID: 41)
            ['county_id' => 41, 'code' => '04101', 'name' => 'Ugenya'],
            ['county_id' => 41, 'code' => '04102', 'name' => 'Ugunja'],
            ['county_id' => 41, 'code' => '04103', 'name' => 'Alego Usonga'],
            ['county_id' => 41, 'code' => '04104', 'name' => 'Gem'],
            ['county_id' => 41, 'code' => '04105', 'name' => 'Bondo'],
            ['county_id' => 41, 'code' => '04106', 'name' => 'Rarieda'],

            // KISUMU (County ID: 42)
            ['county_id' => 42, 'code' => '04201', 'name' => 'Kisumu Central'],
            ['county_id' => 42, 'code' => '04202', 'name' => 'Kisumu East'],
            ['county_id' => 42, 'code' => '04203', 'name' => 'Kisumu West'],
            ['county_id' => 42, 'code' => '04204', 'name' => 'Seme'],
            ['county_id' => 42, 'code' => '04205', 'name' => 'Nyando'],
            ['county_id' => 42, 'code' => '04206', 'name' => 'Muhoroni'],
            ['county_id' => 42, 'code' => '04207', 'name' => 'Nyakach'],

            // HOMA BAY (County ID: 43)
            ['county_id' => 43, 'code' => '04301', 'name' => 'Homa Bay Town'],
            ['county_id' => 43, 'code' => '04302', 'name' => 'Ndhiwa'],
            ['county_id' => 43, 'code' => '04303', 'name' => 'Rangwe'],
            ['county_id' => 43, 'code' => '04304', 'name' => 'Suba'],
            ['county_id' => 43, 'code' => '04305', 'name' => 'Mbita'],
            ['county_id' => 43, 'code' => '04306', 'name' => 'Kabondo'],
            ['county_id' => 43, 'code' => '04307', 'name' => 'Karachuonyo'],
            ['county_id' => 43, 'code' => '04308', 'name' => 'Kaspul'],

            // MIGORI (County ID: 44)
            ['county_id' => 44, 'code' => '04401', 'name' => 'Rongo'],
            ['county_id' => 44, 'code' => '04402', 'name' => 'Awendo'],
            ['county_id' => 44, 'code' => '04403', 'name' => 'Suna East'],
            ['county_id' => 44, 'code' => '04404', 'name' => 'Suna West'],
            ['county_id' => 44, 'code' => '04405', 'name' => 'Uriri'],
            ['county_id' => 44, 'code' => '04406', 'name' => 'Nyatike'],
            ['county_id' => 44, 'code' => '04407', 'name' => 'Kuria West'],
            ['county_id' => 44, 'code' => '04408', 'name' => 'Kuria East'],
            ['county_id' => 44, 'code' => '04409', 'name' => 'Mabera'],
            ['county_id' => 44, 'code' => '04410', 'name' => 'Ntimaru'],

            // KISII (County ID: 45)
            ['county_id' => 45, 'code' => '04501', 'name' => 'Bonchari'],
            ['county_id' => 45, 'code' => '04502', 'name' => 'South Mugirango'],
            ['county_id' => 45, 'code' => '04503', 'name' => 'Bomachoge Borabu'],
            ['county_id' => 45, 'code' => '04504', 'name' => 'Bobasi'],
            ['county_id' => 45, 'code' => '04505', 'name' => 'Bomachoge Chache'],
            ['county_id' => 45, 'code' => '04506', 'name' => 'Nyaribari Masaba'],
            ['county_id' => 45, 'code' => '04507', 'name' => 'Nyaribari Chache'],
            ['county_id' => 45, 'code' => '04508', 'name' => 'Kitutu Chache North'],
            ['county_id' => 45, 'code' => '04509', 'name' => 'Kitutu Chache South'],

            // NYAMIRA (County ID: 46)
            ['county_id' => 46, 'code' => '04601', 'name' => 'Borabu'],
            ['county_id' => 46, 'code' => '04602', 'name' => 'Manga'],
            ['county_id' => 46, 'code' => '04603', 'name' => 'Masaba North'],
            ['county_id' => 46, 'code' => '04604', 'name' => 'Nyamira North'],
            ['county_id' => 46, 'code' => '04605', 'name' => 'Nyamira North'],

            // NAIROBI (County ID: 47)
            ['county_id' => 47, 'code' => '04701', 'name' => 'Westlands'],
            ['county_id' => 47, 'code' => '04702', 'name' => 'Dagoretti North'],
            ['county_id' => 47, 'code' => '04703', 'name' => 'Dagoretti South'],
            ['county_id' => 47, 'code' => '04704', 'name' => 'Lang\'ata'],
            ['county_id' => 47, 'code' => '04705', 'name' => 'Kibra'],
            ['county_id' => 47, 'code' => '04706', 'name' => 'Roysambu'],
            ['county_id' => 47, 'code' => '04707', 'name' => 'Kasarani'],
            ['county_id' => 47, 'code' => '04708', 'name' => 'Ruaraka'],
            ['county_id' => 47, 'code' => '04709', 'name' => 'Embakasi South'],
            ['county_id' => 47, 'code' => '04710', 'name' => 'Embakasi North'],
            ['county_id' => 47, 'code' => '04711', 'name' => 'Embakasi Central'],
            ['county_id' => 47, 'code' => '04712', 'name' => 'Embakasi East'],
            ['county_id' => 47, 'code' => '04713', 'name' => 'Embakasi West'],
            ['county_id' => 47, 'code' => '04714', 'name' => 'Makadara'],
            ['county_id' => 47, 'code' => '04715', 'name' => 'Kamukunji'],
            ['county_id' => 47, 'code' => '04716', 'name' => 'Starehe'],
            ['county_id' => 47, 'code' => '04717', 'name' => 'Mathare'],

            // NAIROBI METROPOLITAN (County ID: 48)
            ['county_id' => 48, 'code' => '04801', 'name' => 'Kajiado Urban'],
            ['county_id' => 48, 'code' => '04802', 'name' => 'Machakos Urban'],
            ['county_id' => 48, 'code' => '04803', 'name' => 'Kiambu Urban'],

        ];

        foreach ($subCounties as $subCounty) {
            SubCounty::create($subCounty);
        }
    }
}
