<?php

namespace Database\Seeders\Custom;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class Seeder1679295400 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $overviews = MenuItem::where('title', 'Overview')->get();
        foreach ($overviews as $overview){
            $overview->update(['icon_class' => $overview->icon_class .' HomeIcon']);
        }

        $identifys = MenuItem::where('title', 'Identify')->get();
        foreach ($identifys as $identify){
            $identify->update(['icon_class' => $identify->icon_class .' FingerPrintIcon']);
        }

        $develops = MenuItem::where('title', 'Develop')->get();
        foreach ($develops as $develop){
            $develop->update(['icon_class' => $develop->icon_class .' BeakerIcon']);
        }

        $reports = MenuItem::where('title', 'Reports')->get();
        foreach ($reports as $report){
            $report->update(['icon_class' => $report->icon_class .' QueueListIcon']);
        }

        $groups = MenuItem::where('title', 'Manage Groups')->get();
        foreach ($groups as $group){
            $group->update(['icon_class' => $group->icon_class .' RectangleGroupIcon']);
        }

        $members = MenuItem::where('title', 'Manage Members')->get();
        foreach ($members as $member){
            $member->update(['icon_class' => $member->icon_class .' UserGroupIcon']);
        }

        $libraries = MenuItem::where('title', 'Library')->get();
        foreach ($libraries as $library){
            $library->update(['icon_class' => $library->icon_class .' PhotoIcon']);
        }

        $trainings = MenuItem::where('title', 'My Training')->get();
        foreach ($trainings as $training){
            $training->update(['icon_class' => $training->icon_class .' ArrowPathRoundedSquareIcon']);
        }

        $xmeareas = MenuItem::where('title', 'XME Area')->get();
        foreach ($xmeareas as $xmearea){
            $xmearea->update(['icon_class' => $xmearea->icon_class .' InboxArrowDownIcon']);
        }

        $leaningpaths = MenuItem::where('title', 'Learning Path')->get();
        foreach ($leaningpaths as $leaningpath){
            $leaningpath->update(['icon_class' => $leaningpath->icon_class .' AcademicCapIcon']);
        }

        $folders = MenuItem::where('title', 'Folders')->get();
        foreach ($folders as $folder){
            $folder->update(['icon_class' => $folder->icon_class .' FolderOpenIcon']);
        }

        $libraryfolders = MenuItem::where('title', 'Library & Folders')->get();
        foreach ($libraryfolders as $libraryfolder){
            $libraryfolder->update(['icon_class' => $libraryfolder->icon_class .' PhotoIcon']);
        }

        $reviewareas = MenuItem::where('title', 'Review Area')->get();
        foreach ($reviewareas as $reviewarea){
            $reviewarea->update(['icon_class' => $reviewarea->icon_class .' ClipboardDocumentListIcon']);
        }

        $groupMembers = MenuItem::where('title', 'Groups & Members')->get();
        foreach ($groupMembers as $groupMember){
            $groupMember->update(['icon_class' => $groupMember->icon_class .' UserIcon']);
        }

        $email = MenuItem::where('title', 'Email')->first();
        $email->update(['icon_class' => $email->icon_class .' EnvelopeIcon']);

        $setting = MenuItem::where('title', 'Settings')->first();
        $setting->update(['icon_class' => $setting->icon_class .' EnvelopeOpenIcon']);
        
        $company = MenuItem::where('title', 'Manage Companies')->first();
        $company->update(['icon_class' => $company->icon_class .' BuildingOfficeIcon']);

        $template = MenuItem::where('title', 'Templates')->first();
        $template->update(['icon_class' => $template->icon_class .' InboxStackIcon']);

        $manageModule = MenuItem::where('title', 'Manage Modules')->first();
        $manageModule->update(['icon_class' => $manageModule->icon_class .' ClipboardDocumentListIcon']);

        $reviewCompanyModule = MenuItem::where('title', 'Review Company Module')->first();
        $reviewCompanyModule->update(['icon_class' => $reviewCompanyModule->icon_class .' WrenchIcon']);

        //
    }
}
