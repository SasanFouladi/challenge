<template>
    <div>
        <b-navbar class="mb-4" toggleable="lg" type="light" variant="white">
            <b-container>
                <b-navbar-brand href="/">Challenge</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <b-nav-item to="/code-management">Codes</b-nav-item>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">

                        <b-nav-item-dropdown right>
                            <!-- Using 'button-content' slot -->
                            <template v-slot:button-content>
                                <em>{{username}}</em>
                            </template>

                            <b-dropdown-item @click="logout">Logout</b-dropdown-item>
                        </b-nav-item-dropdown>
                    </b-navbar-nav>
                </b-collapse>
            </b-container>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "App",
        data() {
            return {
                username: window.username
            }
        },
        methods: {
            /**
             * logout user
             */
            logout() {
                this.$swal({
                    icon: 'question',
                    showCancelButton: true,
                    title: 'Logout',
                    text: `Are You Sure ?`
                })
                    .then(confirm => {
                        if (confirm.value){
                            this.$axios.post('/logout')
                                .then(response => {
                                    window.location.href = '/'
                                })
                        }

                    })
            }
        }
    }
</script>
