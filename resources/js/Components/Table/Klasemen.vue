<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="klasemen"
            :items-per-page="itemsPerPage"
            :sort-by="['point', 'name']"
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
                    <v-dialog
                        v-model="dialog"
                        max-width="600px"
                        :loading="isTeamsLoading"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                rounded
                                class="mb-2 font-bold"
                                v-bind="attrs"
                                v-on="on"
                                @click="loadingTeamsData()"
                            >
                                <v-icon small class="mr-2">fas fa-plus</v-icon>
                                {{ $vuetify.lang.t("$vuetify.match") }}
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="5" md="5">
                                            <v-row>
                                                <v-col cols="12"
                                                    ><v-autocomplete
                                                        :items="teams"
                                                        v-model="
                                                            editedItem.homeTeamId
                                                        "
                                                        :label="
                                                            $vuetify.lang.t(
                                                                '$vuetify.homeTeam'
                                                            )
                                                        "
                                                        :rules="
                                                            homeTeamRequired
                                                        "
                                                    ></v-autocomplete
                                                ></v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="12"
                                                    ><v-text-field
                                                        v-model="
                                                            editedItem.homeTeamScore
                                                        "
                                                        :label="
                                                            $vuetify.lang.t(
                                                                '$vuetify.score'
                                                            )
                                                        "
                                                        :rules="required"
                                                    ></v-text-field
                                                ></v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="2"
                                            md="2"
                                            class="d-flex align-center justify-center"
                                        >
                                            <div class="text-h6">
                                                {{
                                                    $vuetify.lang.t(
                                                        "$vuetify.vs"
                                                    )
                                                }}
                                            </div>
                                        </v-col>
                                        <v-col cols="12" sm="5" md="5">
                                            <v-row>
                                                <v-col cols="12"
                                                    ><v-autocomplete
                                                        :items="teams"
                                                        v-model="
                                                            editedItem.awayTeamId
                                                        "
                                                        :label="
                                                            $vuetify.lang.t(
                                                                '$vuetify.awayTeam'
                                                            )
                                                        "
                                                        :rules="
                                                            awayTeamRequired
                                                        "
                                                    ></v-autocomplete
                                                ></v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="12"
                                                    ><v-text-field
                                                        v-model="
                                                            editedItem.awayTeamScore
                                                        "
                                                        :label="
                                                            $vuetify.lang.t(
                                                                '$vuetify.score'
                                                            )
                                                        "
                                                        :rules="required"
                                                    ></v-text-field
                                                ></v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    rounded
                                    color="grey darken-1"
                                    text
                                    @click="close"
                                    class="px-4"
                                >
                                    {{ $vuetify.lang.t("$vuetify.cancel") }}
                                </v-btn>
                                <v-btn
                                    rounded
                                    color="primary"
                                    class="font-bold px-4"
                                    @click="save"
                                >
                                    <v-icon small class="mr-2"
                                        >fas fa-save</v-icon
                                    >
                                    {{ $vuetify.lang.t("$vuetify.save") }}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
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
        <v-snackbar v-model="snackbar" :timeout="3000">
            {{ snackbarMessage }}
            <template v-slot:action="{ attrs }">
                <v-btn icon x-small v-bind="attrs" @click="snackbar = false">
                    <v-icon x-small>fas fa-window-close</v-icon>
                </v-btn>
            </template>
        </v-snackbar>
    </div>
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
            dialog: false,
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
            klasemen: [],
            teams: [],
            isTeamsLoading: false,
            snackbar: false,
            snackbarMessage: "",
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
                    text: this.$vuetify.lang.t("$vuetify.name"),
                    align: "start",
                    value: "name",
                },
                {
                    text: this.$vuetify.lang.t("$vuetify.point"),
                    value: "point",
                },
            ];
        },
    },
    watch: {
        dialog(val) {
            val || this.close();
        },
    },
    methods: {
        loadingTeamsData() {
            let self = this;
            self.teams = [];
            self.isTeamsLoading = true;

            axios
                .get("/api/team")
                .then(function (response) {
                    console.log(response);

                    response.data.forEach((team) => {
                        if (team) {
                            self.teams.push({
                                value: team.id,
                                text: team.name,
                            });
                        }
                    });
                })
                .catch(function (response) {
                    console.error(response);
                })
                .then(function () {
                    self.isTeamsLoading = false;
                });
        },
        loadingData() {
            let self = this;
            self.klasemen = [];
            self.isLoading = true;

            axios
                .get("/api/klasemen")
                .then(function (response) {
                    console.log(response);

                    response.data.forEach((peringkat) => {
                        if (peringkat) {
                            self.klasemen.push({
                                name: peringkat.team.name,
                                point: peringkat.point,
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

        close() {
            this.dialog = false;
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
        },

        save() {
            let self = this;

            if (this.editedIndex > -1) {
                axios
                    .put(
                        `/api/pertandingan/${this.editedIndex}`,
                        self.editedItem
                    )
                    .then(function (response) {
                        console.log(response);

                        self.snackbarMessage = self.$vuetify.lang.t(
                            "$vuetify.matchSuccessfullyEdited"
                        );

                        self.close();
                        self.snackbar = true;
                        self.loadingData();
                    })
                    .catch(function (response) {
                        console.error(response);
                    })
                    .then(function () {});
            } else {
                // this.customers.push(this.editedItem);

                axios
                    .post("/api/pertandingan", self.editedItem)
                    .then(function (response) {
                        console.log(response);

                        self.snackbarMessage = self.$vuetify.lang.t(
                            "$vuetify.matchSuccessfullyAdded"
                        );

                        self.close();
                        self.snackbar = true;
                        self.loadingData();
                    })
                    .catch(function (response) {
                        console.error(response);
                    })
                    .then(function () {});
            }
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