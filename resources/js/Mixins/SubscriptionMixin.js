// define a mixin object
export default {
    methods: {
        getClientReferenceId() {
            return window.Rewardful && window.Rewardful.referral || ('checkout_'+(new Date).getTime());
        }
    }
}