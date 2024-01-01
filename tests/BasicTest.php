<?php

namespace Lodestone\Tests;

use PHPUnit\Framework\TestCase;

final class BasicTest extends TestCase
{
    public function test(): void
    {
        // create api instance
        $api = new \Lodestone\Api();

        // Easy adjusting of tests
        $user = '9575452';
        $expectedUserName = 'Arcane Disgea';
        $fc = '9232379236109629819';
        $expectedfc = 'Hell On Aura';
        $ls = '18014398509568031';
        $pvp = '59665d98bf81ff58db63305b538cd69a6c64d578';
        $bio = "This is a test of the emergency alert system.\nAHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH\n\nECU125614VC";

        $character = $api->character()->get($user);
        self::assertSame($expectedUserName, $character->Name);
        self::assertSame($bio, $character->Bio);

        self::assertNotEmpty($api->character()->friends($user));
        self::assertNotEmpty($api->character()->following($user));
        // self::assertTrue($api->character()->achievements($user)->PointsTotal > 0); AHHHHHHHHHHHHHHHH
        // self::assertNotEmpty($api->getCharacterAchievementsFull($user)->Achievements); This may not be relevant anymore
        self::assertSame($expectedfc, $api->FreeCompany()->get($fc)->Name);
        // self::assertSame($api->getFreeCompanyFull('9233927348481473031')->Profile->ID, '9233927348481473031'); This may not be relevant anymore
        self::assertNotEmpty($api->FreeCompany()->members($fc)->Results);
        self::assertNotEmpty($api->Linkshell()->get($ls)->Results);
        self::assertNotEmpty($api->PvPTeam()->get($pvp)->Results);
        self::assertNotEmpty($api->Character()->search($expectedUserName)->Results);
        self::assertNotEmpty($api->FreeCompany()->search('a')->Results);
        self::assertNotEmpty($api->Linkshell()->search('a')->Results);
        self::assertNotEmpty($api->PvPTeam()->search('a')->Results);
        self::assertNotEmpty($api->lodestone()->banners());
        //self::assertNotEmpty($api->lodestone()->News());
        //self::assertNotEmpty($api->lodestone()->Topics());
        //self::assertNotEmpty($api->lodestone()->Notices());
        //self::assertNotEmpty($api->lodestone()->Maintenance());
        //self::assertNotEmpty($api->lodestone()->Updates());
        //self::assertNotEmpty($api->lodestone()->Status());
        self::assertNotEmpty($api->lodestone()->WorldStatus());
        self::assertNotEmpty($api->devposts()->blog());
        # self::assertNotEmpty($api->getDevPosts()); - this takes agessssss
        self::assertNotEmpty($api->leaderboards()->feast());
        self::assertNotEmpty($api->leaderboards()->ddPalaceOfTheDead());
        self::assertNotEmpty($api->leaderboards()->ddHeavenOnHigh());

    }
}
