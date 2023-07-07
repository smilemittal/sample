<template>
    <div>

        <InertiaHead title="Manage Groups" />
        <AuthenticatedLayout>
            <div class="mb-4">
                <div class="flex items-center flex-wrap">
                    <div class="page-header">
                        <inertia-link :href="route('admin.manage-groups')"
                            class="text-neutral-300 font-bold text-xl sm:text-2xl md:text-4xl">{{ trans("lang.labels.manage_groups") }}</inertia-link>
                        <h5 class="text-sm sm:text-base text-neutral-400 mt-2">{{ trans("lang.labels.manage_members_and_modules") }}
                        </h5>
                        <button class="bg-btnSubmitBg px-2 py-1 rounded-lg text-btnSubmitText w-fit mt-2"
                            @click="this.isEditGroup = true"><span class="themeManageButton">{{ this.group.name
                            }}</span></button>
                    </div>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <!----Manage Groups Table------->
                <members ref="members" @openDeleteMember="isDeleMember" @openMember="openMember()" @openDate="openDate"
                    :id="id"></members>
                <modules ref="modules" @openDeleteModule="isDeleModule" @openModule="openModule()" @openAssign="openAssign"
                    @openAssignSetting="openAssignSetting" :id="id"></modules>
            </div>
            <delete-member @openDeleteMember="isDeleMember" @closeDelete="isCloseDeleteMember()" v-if="isDeleteMember"
                :userId="memberId" :groupId="id"></delete-member>
            <delete-module @openDeleteModule="isDeleModule" @closeDeleteModule="isCloseDeleteModule()" v-if="isDeleteModule"
                :formId="formId" :groupId="id"></delete-module>
            <add-member v-if="isMember" @closeMember="closeMember()" :id="id"></add-member>
            <edit-group v-if="isEditGroup" :groupId="id" @closeEdit="closeEdit()"></edit-group>
            <add-module v-if="isModule" @closeModule="closeModule()" :id="id"></add-module>
            <schedule-date @closeDate="closeDate()" v-if="isDate" :groupId="this.id"
                :memberId="this.memberId"></schedule-date>
            <reassign-module v-if="isAssign" @closeAssign="closeAssign()" :id="id" :formId="formId"></reassign-module>
            <remove-reassign-setting v-if="isAssignSetting" @closeAssignSetting="closeAssignSetting()" :groupId="id"
                :formId="formId"></remove-reassign-setting>
        </AuthenticatedLayout>
    </div>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteMember from '@/Components/ManageGroups/DeleteMember.vue';
import DeleteModule from '@/Components/ManageGroups/DeleteModule.vue';
import AddMember from '@/Components/ManageGroups/AddMember.vue'
import ReassignModule from '@/Components/ManageGroups/ReassignModule.vue'
import AddModule from '@/Components/ManageGroups/AddModule.vue'
import Members from '@/Components/ManageGroups/Tables/Members.vue'
import Modules from '@/Components/ManageGroups/Tables/Modules.vue'
import ScheduleDate from '@/Components/ManageGroups/ScheduleDate.vue'
import EditGroup from '@/Components/ManageGroups/Edit.vue'
import RemoveReassignSetting from '@/Components/ManageGroups/ReassignSettings.vue'

export default {
    props: ['id'],
    name: 'Manage Groups',
    components: {
        AuthenticatedLayout,
         
        DeleteMember,
        DeleteModule,
        Members,
        Modules,
        AddMember,
        AddModule,
        ReassignModule,
        ScheduleDate,
        EditGroup,
        RemoveReassignSetting
    },
    data() {
        return {
            group: [],
            isMember: false,
            isDelete: false,
            isModule: false,
            memberId: '',
            formId: '',
            isDeleteMember: false,
            isDeleteModule: false,
            isEditGroup: false,
            isAssign: false,
            isDate: false,
            isAssignSetting: false
        }
    },
    watch: {
        isMember: function () {
            document.body.style.overflow = this.isMember ? 'hidden' : ''
        },
        isEditGroup: function () {
            document.body.style.overflow = this.isEditGroup ? 'hidden' : ''
        },
        isDelete: function () {
            document.body.style.overflow = this.isDelete ? 'hidden' : ''
        },
        isModule: function () {
            document.body.style.overflow = this.isModule ? 'hidden' : ''
        },
        isDeleteMember: function () {
            document.body.style.overflow = this.isDeleteMember ? 'hidden' : ''
        },
        isDeleteModule: function () {
            document.body.style.overflow = this.isDeleteModule ? 'hidden' : ''
        },
        isAssign: function () {
            document.body.style.overflow = this.isAssign ? 'hidden' : ''
        }
    },
    methods: {
        isCloseDelete() {
            this.isDelete = false;
        },
        isDateOpen: function (userId) {
            this.isDate = true;
            this.memberId = userId
        },
        isDeleMember: function (userId) {
            this.isDeleteMember = true;
            this.memberId = userId;
        },
        isDeleModule: function (moduleId) {
            this.isDeleteModule = true;
            this.formId = moduleId;
        },
        isCloseDeleteMember() {
            this.isDeleteMember = false;
            this.$refs.members.createTable(1);
        },
        isCloseDeleteModule() {
            this.isDeleteModule = false;
            this.$refs.modules.createTable(1);
        },
        isDeleteOpen() {
            this.isDelete = true;
        },
        openMember() {
            this.isMember = true;
        },
        closeMember() {
            this.isMember = false;
            this.$refs.members.createTable(1);
        },
        openModule() {
            this.isModule = true;
        },
        closeModule() {
            this.isModule = false;
            this.$refs.modules.createTable(1);
        },
        openAssign: function (id) {
            this.isAssign = true;
            this.formId = id;
        },
        closeAssign() {
            this.isAssign = false;
            this.$refs.modules.createTable(1);
        },
        openDate: function (memberId) {
            this.isDate = true
            this.memberId = memberId;
        },
        closeDate() {
            this.isDate = false;
            this.$refs.members.createTable(1);
        },
        closeEdit() {
            this.isEditGroup = false;
            this.edit();
        },
        openAssignSetting: function (formId) {
            this.formId = formId;
            this.isAssignSetting = true;
        },
        closeAssignSetting() {
            this.isAssignSetting = false;
            this.$refs.modules.createTable(1);
        },
        edit() {
            axios.get('/api/edit-group/' + this.id)
                .then((response) => {
                    if (response.status == 200) {
                        this.group = response.data.data;
                    }
                })
        },
    },
    created: function () {
        this.edit();
    },
}
</script>
