<template>
    <div>

        <InertiaHead title="Learning Path" />
        <AuthenticatedLayout>
            <div class="mb-4">
                <div class="flex items-center flex-wrap">
                    <div class="page-header">
                        <inertia-link :href="route('app.learning-path')"
                            class="text-neutral-300 font-bold text-2xl sm:text-2xl md:text-4xl">{{ trans('lang.labels.your_learning_path') }}</inertia-link>
                        <h5 class="text-neutral-400 text-sm sm:text-sm md:text-base">{{ trans('lang.labels.a_series_of_modules') }}
                        </h5>
                        <button type="button" @click="openEdit()"
                            class="bg-btnSubmitBg px-2 py-1 rounded-lg text-btnSubmitText breakWord mt-1">
                            <span class="themeManageButton">{{ learningpath.title }}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="h-full drop-shadow-md rounded-3xl mt-5">
                <!----Your learning path table------->
                <members ref="members" @openDelete="isDeleteOpen()" @openDeleteMember="isDeleMember" @openDate="isDateOpen"
                    @openLock="isLockOpen()" @openMember="isMemberOpen()" @openMemberLock="isLockMemberOpen()" :id="id">
                </members>
                <modules ref="modules" :id="id" @openLock="isLockOpen()" @openModule="isOpenModule()" @lock="isLock()"
                    :lock="lock" @openDeleteModule="isDeleModule">
                </modules>
            </div>
            <add-member @openMember="isMemberOpen()" @closeMember="isCloseMember()" v-if="isMember" :id="id"></add-member>
            <schedule-date @openDate="isDateOpen" @closeDate="isCloseDate()" v-if="isDate" :learningPathId="id"
                :userId="memberId"></schedule-date>
            <add-path @openPath="isPathOpen()" @closeAdd="isCloseAdd()" v-if="isAdd"></add-path>
            <edit-path @closeEdit="isCloseEdit()" v-if="isEdit" :learningPathId="id"
                :userRole="loggedUserRole"></edit-path>
            <lock-path @closeLock="isLockClose()" v-if="isLock" :id="id"></lock-path>
            <lock-member @closeMemberLock="isLockMemberClose()" v-if="isLockMember"></lock-member>
            <add-module @closeModule="isCloseModule()" v-if="isModule" :id="id"></add-module>
            <delete-member @openDeleteMember="isDeleMember" :learningPathId="id" :userId="memberId"
                @closeDelete="isCloseDelete()" v-if="isDeleteMember"></delete-member>
            <delete-module @openDeleteModule="isDeleModule" :learningPathId="id" :formId="formId"
                @closeDeleteModule="isCloseDeleteModule()" v-if="isDeleteModule"></delete-module>
        </AuthenticatedLayout>
    </div>
</template>
<script>
import {
    TrashIcon, XCircleIcon
} from '@heroicons/vue/24/solid'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddMember from '@/Components/LearningPath/AddMember.vue'
import ScheduleDate from '@/Components/LearningPath/ScheduleDate.vue'
import AddPath from '@/Components/LearningPath/AddLearning.vue'
import EditPath from '@/Components/LearningPath/EditLearning.vue'
import Members from '@/Components/LearningPath/Tables/Members.vue'
import Modules from '@/Components/LearningPath/Tables/Modules.vue'
import LockPath from '@/Components/LearningPath/LockLearning.vue'
import LockMember from '@/Components/LearningPath/LockMember.vue'
import AddModule from '@/Components/LearningPath/AddModule.vue'
import DeleteMember from '@/Components/LearningPath/DeleteMember.vue';
import DeleteModule from '@/Components/LearningPath/DeleteModule.vue';


export default {
    props: ['id', 'user'],
    components: {
        AuthenticatedLayout,
         
        AddMember,
        ScheduleDate,
        DeleteMember,
        DeleteModule,
        AddPath,
        Members,
        Modules,
        EditPath,
        LockPath,
        AddModule,
        LockMember,
        TrashIcon, XCircleIcon
    },
    data() {
        return {
            learningpath: [],
            isMember: false,
            isDate: false,
            isDelete: false,
            isAdd: false,
            isEdit: false,
            isLock: false,
            isModule: false,
            isLockMember: false,
            isDeleteAction: false,
            memberId: '',
            isDeleteMember: false,
            isDeleteModule: false,
            loggedUserRole: '',
        }
    },
    methods: {
        isCloseMember() {
            this.isMember = false;
            this.$refs.members.createTable();
            document.body.style.overflow = '';
        },
        isCloseDate() {
            this.isDate = false;
            this.$refs.members.refresh();
            document.body.style.overflow = '';
        },
        isCloseDelete() {
            this.isDeleteMember = false;
            this.$refs.members.refresh();
            document.body.style.overflow = '';
        },
        isCloseDeleteModule() {
            this.isDeleteModule = false;
            this.$refs.modules.refresh();
            document.body.style.overflow = '';
        },
        isCloseAdd() {
            this.isAdd = false;
            document.body.style.overflow = '';
        },
        openEdit(){
            this.isEdit = true;
            document.body.style.overflow = 'hidden';
        },
        isCloseEdit() {
            this.isEdit = false;
            this.edit();
            document.body.style.overflow = '';
        },
        isDateOpen: function (userId) {
            this.isDate = true;
            this.memberId = userId
            document.body.style.overflow = 'hidden';
        },
        isDeleMember: function (userId) {
            this.isDeleteMember = true;
            this.memberId = userId
            document.body.style.overflow = 'hidden';
        },
        isDeleModule: function (formId) {
            this.isDeleteModule = true;
            this.formId = formId;
            document.body.style.overflow = 'hidden';
        },
        isDeleteOpen() {
            this.isDelete = true;
            document.body.style.overflow = 'hidden';
        },
        isMemberOpen() {
            this.isMember = true;
            document.body.style.overflow = 'hidden';
        },
        isAddOpen() {
            this.isAdd = true;
            document.body.style.overflow = 'hidden';
        },
        isLockOpen() {
            this.isLock = true;
            document.body.style.overflow = 'hidden';
        },
        isLockClose() {
            this.isLock = false;
            this.$refs.modules.refresh();
            this.$refs.members.refresh();
            document.body.style.overflow = '';
        },
        isLockMemberOpen() {
            this.isLockMember = true;
            document.body.style.overflow = 'hidden';
        },
        isLockMemberClose() {
            this.isLockMember = false;
            document.body.style.overflow = '';
        },
        isOpenModule() {
            this.isModule = true;
            document.body.style.overflow = 'hidden';
        },
        isCloseModule() {
            this.isModule = false;
            this.$refs.modules.createTable();
            document.body.style.overflow = '';
        },
        edit() {
            axios.get('/api/edit-learning-path/' + this.id)
                .then((response) => {
                    if (response.status = true) {
                        this.learningpath = response.data.data;
                    }
                })
        },
    },
    created: function () {
        this.edit();
        this.loggedUserRole = this.user.role.name;
    },
}
</script>
