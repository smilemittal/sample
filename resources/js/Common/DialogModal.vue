<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" :mainClass="mainClass" :headerClass="headerClass" :extraFlex="extraFlex" @close="close" :extraClass="extraClass" :footerColor="footerColor">
        <div class="px-6 py-4" :class="headerClass">
            <div class="text-lg">
                <slot name="header">
                </slot>
            </div>

            <div class="mt-4" :class="mainClass">
                <slot name="content">
                </slot>
            </div>
        </div>

        <div class="px-6 py-4 bg-body text-right" :class="footerColor"  v-if="$slots.footer">
            <slot name="footer">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal.vue'

    export default {
        emits: ['close'],

        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            headerClass: {
                default: '',
                require:false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
            extraClass: {
                default: '',
                require:false
            },
            mainClass: {
                default: '',
                require:false
            },
            footerColor: {
                default: '',
                require:false
            },
            extraFlex: {
                default: '',
                require:false
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
<style scoped>
.modal-padding{
   padding: 0 !important;
}
.content-padding {
    margin-top: 0 !important;
}
</style>
