<?php

namespace Database\Seeders\Custom;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class Seeder1685421885 extends Seeder
{
    public function run()
    {
        $companyOwnerMenu = Menu::where('name', 'Company Owner')->firstOrFail();

        $companyOwnerMenuItem = MenuItem::firstOrNew([
            'menu_id' => $companyOwnerMenu->id,
            'title'   => 'In Production',
            'url'     => '',
            'route'   => 'app.in-production-modules',
        ]);
        if (!$companyOwnerMenuItem->exists) {
            $companyOwnerMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ClipboardDocumentListIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 6,
            ])->save();
        }

        $businessAdminMenu = Menu::where('name', 'businessadmin')->firstOrFail();
        $memberAdminMenu = Menu::where('name', 'member')->firstOrFail();

        $businessAdminMenuItem = MenuItem::firstOrNew([
            'menu_id' => $businessAdminMenu->id,
            'title'   => 'In Production',
            'url'     => '',
            'route'   => 'app.in-production-modules',
        ]);
        if (!$businessAdminMenuItem->exists) {
            $businessAdminMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'ClipboardDocumentListIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 6,
            ])->save();
        }

        $memberKnowledgeBank = MenuItem::where('menu_id', $memberAdminMenu->id)->where('title', 'Library')->first();
        if ($memberKnowledgeBank) {
            $memberKnowledgeBank->update([
                'title' => 'Knowledge Bank'
            ]);
        }

        $memberXmeSupport = MenuItem::where('menu_id', $memberAdminMenu->id)->where('title', 'XME Area')->first();
        if ($memberXmeSupport) {
            $memberXmeSupport->update([
                'title' => 'XME Support',
                'order' => '4',
            ]);
        }

        $companyIdentifyAreas = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Identify')->get();
        if ($companyIdentifyAreas) {
            foreach ($companyIdentifyAreas as $companyIdentifyArea) {
                $companyIdentifyArea->update([
                    'title' => 'Identify Area',
                ]);
            }
        }

        $companyDevelopAreas = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Develop')->get();
        if ($companyDevelopAreas) {
            foreach ($companyDevelopAreas as $companyDevelopArea) {
                $companyDevelopArea->update([
                    'title' => 'Develop Area',
                ]);
            }
        }

        $companyKnowledgeBank = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Library & Folders')->get();
        if ($companyKnowledgeBank) {
            foreach ($companyKnowledgeBank as $identifyArea) {
                $identifyArea->update([
                    'title' => 'Knowledge Bank',
                    'order' => '7'
                ]);
            }
        }

        $companyLeaningpaths = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Learning Path')->get();
        if ($companyLeaningpaths) {
            foreach ($companyLeaningpaths as $companyLeaningpath) {
                $companyLeaningpath->update([
                    'order' => '8',
                ]);
            }
        }

        $companyDeliverKnowledges = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Groups & Members')->get();
        if ($companyDeliverKnowledges) {
            foreach ($companyDeliverKnowledges as $companyDeliverKnowledge) {
                $companyDeliverKnowledge->update([
                    'title' => 'Deliver Knowledge',
                    'order' => '9',
                ]);
            }
        }

        $companyGroups = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Manage Groups')->get();
        if ($companyGroups) {
            foreach ($companyGroups as $companyGroup) {
                $companyGroup->update([
                    'title' => 'Groups',
                ]);
            }
        }

        $companyMembers = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Manage Members')->get();
        if ($companyMembers) {
            foreach ($companyMembers as $companyMember) {
                $companyMember->update([
                    'title' => 'Members',
                ]);
            }
        }

        $companyDocumentReports = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Reports')->get();
        if ($companyDocumentReports) {
            foreach ($companyDocumentReports as $companyDocumentReport) {
                $companyDocumentReport->update([
                    'title' => 'Document / Reports',
                    'order' => '10',
                ]);
            }
        }

        $companyLiveModules = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'Review Area')->get();
        if ($companyLiveModules) {
            foreach ($companyLiveModules as $companyLiveModule) {
                $companyLiveModule->update([
                    'title' => 'Live Modules',
                    'order' => '11',
                ]);
            }
        }

        $companyXmeAreas = MenuItem::whereIn('menu_id', [$businessAdminMenu->id, $companyOwnerMenu->id])
            ->where('title', 'XME Area')->get();
        if ($companyXmeAreas) {
            foreach ($companyXmeAreas as $companyXmeArea) {
                $companyXmeArea->update([
                    'title' => 'XME Support',
                    'order' => '12',
                ]);
            }
        }
    }
}
