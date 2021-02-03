<template>
    <v-data-table
        :headers="headers"
        :items="pertandingans"
        :items-per-page="itemsPerPage"
        :sort-by="['createdAt']"
        :sort-desc="[true]"
        class="elevation-2"
        :loading="isLoading"
        :search="search"
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    :label="$vuetify.lang.t('$vuetify.search')"
                    single-line
                    hide-details
                    class="mr-4"
                >
                    <template v-slot:append>
                        <v-icon small>fas fa-search</v-icon>
                    </template>
                </v-text-field>
            </v-toolbar>
        </template>
        <template v-slot:item.createdAt="{ item }">
            <span>{{ new Date(item.createdAt).toLocaleString() }}</span>
        </template>
        <template v-slot:no-data>
            <v-btn
                rounded
                color="primary"
                class="font-bold"
                @click="loadingData"
            >
                <v-icon small class="mr-2">fas fa-redo</v-icon>
                {{ $vuetify.lang.t("$vuetify.reload") }}
            </v-btn>
        </template>
    </v-data-table>
</template>
<script>
export default {
    mounted() {
        this.loadingData();
    },
    props: {
        itemsPerPage: {
            type: Number,
            default: 10,
        },
    },
    data() {
        return {
            required: [
                (v) => !!v || this.$vuetify.lang.t("$vuetify.fieldIsRequired"),
            ],
            homeTeamRequired: [
                (v) => !!v || this.$vuetify.lang.t("$vuetify.fieldIsRequired"),
                (v) =>
                    !(
                        this.editedItem.homeTeamId ===
                        this.editedItem.awayTeamId
                    ) ||
                    this.$vuetify.lang.t(
                        "$vuetify.homeTeamCanNotBeSameWithAwayTeam"
                    ),
            ],
            awayTeamRequired: [
                (v) => !!v || this.$vuetify.lang.t("$vuetify.fieldIsRequired"),
                (v) =>
                    !(
                        this.editedItem.awayTeamId ===
                        this.editedItem.homeTeamId
                    ) ||
                    this.$vuetify.lang.t(
                        "$vuetify.awayTeamCanNotBeSameWithHomeTeam"
                    ),
            ],
            search: "",
            isLoading: false,
            editedIndex: -1,
            editedItem: {
                homeTeamId: "",
                awayTeamId: "",
                homeTeamScore: "",
                awayTeamScore: "",
            },
            defaultItem: {
                homeTeamId: "",
                awayTeamId: "",
                homeTeamScore: "",
                awayTeamScore: "",
            },
            pertandingans: [],
            teams: [],
            isTeamsLoading: false,
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? this.$vuetify.lang.t("$vuetify.addMatch")
                : this.$vuetify.lang.t("$vuetify.editMatch");
        },
        headers() {
            return [
                {
                    text: this.$vuetify.lang.t("$vuetify.homeTeam"),
                    align: "start",
                    value: "homeTeam.name",
                },
                {
                    text: this.$vuetify.lang.t("$vuetify.score"),
                    value: "score",
                },
                {
                    text: this.$vuetify.lang.t("$vuetify.awayTeam"),
                    value: "awayTeam.name",
                },
                {
                    text: this.$vuetify.lang.t("$vuetify.createdAt"),
                    value: "createdAt",
                },
            ];
        },
    },
    watch: {},
    methods: {
        loadingData() {
            let self = this;
            self.pertandingans = [];
            self.isLoading = true;

            axios
                .get("/api/pertandingan")
                .then(function (response) {
                    console.log(response);

                    response.data.forEach((pertandingan) => {
                        if (pertandingan) {
                            self.pertandingans.push({
                                homeTeamId: pertandingan.home_team_id,
                                awayTeamId: pertandingan.away_team_id,
                                homeTeamScore: pertandingan.home_team_score,
                                awayTeamScore: pertandingan.away_team_score,
                                score:
                                    pertandingan.home_team_score +
                                    "-" +
                                    pertandingan.away_team_score,
                                homeTeam: pertandingan.home_team,
                                awayTeam: pertandingan.away_team,
                                createdAt: pertandingan.created_at,
                            });
                        }
                    });
                })
                .catch(function (response) {
                    console.error(response);
                })
                .then(function () {
                    self.isLoading = false;
                });
        },
    },
};
</script>
<style>
[type="text"],
[type="email"],
[type="url"],
[type="password"],
[type="number"],
[type="date"],
[type="datetime-local"],
[type="month"],
[type="search"],
[type="tel"],
[type="time"],
[type="week"],
[multiple],
textarea,
select {
    @apply ring-0;
    @apply bg-transparent;
}
.v-data-table-header th.sortable {
    white-space: nowrap;
}
</style>