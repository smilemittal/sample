<template>
  <div class="grid grid-cols-1 gap-3">
    <div class="" v-for="(question, index) in questions" :key="index">
      <template v-if="question.type === 'contact_info'">
        <div v-for="(cfield, cindex) in question.properties.fields" :key="cindex">
          <template v-if="hasAnswer(cfield.ref)">
            <p class="text-sm text-gray-500 dark:text-gray-400 bg-cardtop p-3 rounded-lg">
              <span class="item-heading block">{{ cfield.title.replace("hidden:name",hidden.name).replace("hidden:email",hidden.email) }}</span>
              <span class="item-answer block mt-3 text-amber-500">{{ getAnswer(cfield.ref) }}</span>
            </p>
          </template>
        </div>
      </template>
      <template v-else>
        <template v-if="hasAnswer(question.ref)">
          <p class="text-sm text-gray-500 dark:text-gray-400 bg-cardtop p-3 rounded-lg">
            <span class="item-heading block">{{ question.title.replace("hidden:name",hidden.name).replace("hidden:email",hidden.email) }}</span>
            <span class="item-answer block mt-3 text-amber-500">{{ getAnswer(question.ref) }}</span>
          </p>
        </template>
      </template>
    </div>
  </div>
</template>
  
<script>

export default {
  props: {
    questions: {
      type: Array,
      required: true
    },
    answers: {
      type: Array,
      required: false
    },
    hidden: {
      type: Object,
      required: false
    }
  },
  computed: {
    answerMap() {
      const map = new Map()
      if (this.answers) {
        this.answers.forEach(answer => {
          map.set(answer.field.ref, answer)
        })
      }
      return map
    },
  },
  methods: {
    hasAnswer(ref) {
      return this.answerMap.has(ref)
    },
    getAnswer(ref) {
      const singleAnswer = this.answerMap.get(ref)
      if (singleAnswer && Object.keys(singleAnswer).length > 0) {
        let keys = Object.keys(singleAnswer);
        let output = singleAnswer[keys[2]];
        if (typeof output === "string") {
          // output = "STRING";
        } else if (typeof output === "boolean") {
          // output = "BOOLEAN";
        } else if (Array.isArray(output) || output instanceof Object) {
          if (output.label) {
            output = output.label;
          } else {
            output = singleAnswer[keys[1]];
            if (typeof output === "string") {
              // output = "STRING";
            } else if (typeof output === "boolean") {
              // output = "BOOLEAN";
            } else if (typeof output === "object" || Array.isArray(output)) {
              if (output.label) {
                output = output.label;
              } else {
                output = output.labels.join(';');
              }
            }
          }
        } else {
          output = 'TYPE:UNKNOWN';
        }
        return !output || String(output).match(/^http(s)?:\/\//) ? `<a href="${output}" download>${output}</a>` : output;
      }
    }
  }
}
</script>
  