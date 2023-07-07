export default class StripePayment {

    constructor() {
        this.stripe = import.meta.env.VITE_STRIPE_KEY ? Stripe(import.meta.env.VITE_STRIPE_KEY) : null;
        this.card = this.stripe.elements().create("card", this.cardStyles());
    }

    mountTo(container) {
        this.card.mount(container);
        return this;
    }

    getCardElement() {
        return this.card;
    }

    cardStyles() {
        return {
            hidePostalCode: true,
            style: {
                base: {
                    fontWeight: "300",
                    fontSize: "16px",
                    color: "#22292f",
                    "::placeholder": {color: "rgb(146, 148, 151)"}
                }
            }
        }
    }

    createToken(callback) {
        this.stripe.createToken(this.card).then(callback);
    }

    createPaymentMethod(email, name, callback) {
        this.stripe.createPaymentMethod({
            type: 'card',
            card: this.card,
            billing_details: {
                email: email,
                name: name
            },
        }).then(callback);
    }

    confirmCardSetup(email, name, cardElement, clientSecret, callback) {
        this.stripe.confirmCardSetup(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    email: email,
                    name: name
                },
            }
        }).then(callback);
    }

    onChange(callback) {
        this.card.addEventListener('change', callback);
    }
}
