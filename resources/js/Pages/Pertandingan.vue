<template>
    <layout
        ><div class="text-h4 mt-6 mb-4">
            {{ $vuetify.lang.t("$vuetify.title.matches") }}
        </div>
        <v-row>
            <v-col>
                <pertandingan :iemsPerPage="8"></pertandingan>
            </v-col>
        </v-row>
    </layout>
</template>
<script>
import Layout from "@/Layouts/Layout";
import Pertandingan from "@/Components/Table/Pertandingan";

export default {
    title() {
        if (this.title) {
            let translationTitle = this.$vuetify.lang.t(
                "$vuetify.title." + this.title
            );
            if (translationTitle) {
                return translationTitle;
            } else {
                return _.capitalize(this.title);
            }
        }
    },

    components: {
        Layout,
        Pertandingan,
    },

    props: {
        currentRouteName: {
            type: String,
        },
        errorBags: {
            type: Array,
        },
        errors: {
            type: Object,
        },
        jetstream: {
            type: Object,
        },
        users: {
            type: Array,
        },
        title: {
            type: String,
        },
    },

    data() {
        return {};
    },

    computed: {},

    watch: {},

    methods: {},
};
</script>
