<template>
    <div :class="{ 'toggled': isSidebarToggled }" id="wrapper" v-if="isLoggedIn">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <h2>Logo</h2>
            </div>
            <ul class="sidebar-nav">
                <li :class="{ active: isActive('/dashboard') }">
                    <router-link to="/dashboard" class="nav-item nav-link"><font-awesome-icon class="fa" icon="fa fa-home"/>Dashboard</router-link>
                </li>
                <li :class="{ active: isActive('/items') }">
                    <router-link to="/items" class="nav-item nav-link"><font-awesome-icon class="fa" icon="fa fa-file"/>Items</router-link>
                </li>
                <li :class="{ active: isActive('/categories') }">
                    <router-link to="/categories" class="nav-item nav-link"><font-awesome-icon class="fa" icon="fa fa-user"/>Categories</router-link>
                </li>
            </ul>
        </aside>

        <div id="navbar-wrapper">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid d-block">
                    <div class="navbar-header">
                        <a @click="toggleSidebar" class="navbar-brand" id="sidebar-toggle">
                            <font-awesome-icon icon="bars" />
                        </a>
                        <a class="navbar-brand float-end" style="cursor: pointer;" @click="logout"><font-awesome-icon class="fas" icon="sign-out"/>logout</a>
                    </div>
                </div>
            </nav>
        </div>

        <section id="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="container-fluid">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div v-else>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    name: "App",
    data() {
        return {
            isSidebarToggled: false,
            isLoggedIn: false,
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
    },
    methods: {
        isActive(route) {
            return this.$route.path === route;
        },
        toggleSidebar() {
            console.log('dd')
            this.isSidebarToggled = !this.isSidebarToggled;
        },
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/user/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}


</script>
<style scoped>
body {
    padding-bottom: 30px;
    position: relative;
    min-height: 100%;
}

a {
    transition: background 0.2s, color 0.2s;
}
.fa{
  font-size: 19px;
  width: 40px;
  padding-right: 10px;
}

.fas{
  font-size: 15px;
  width: 20px;
  padding-right: 5px;
}
.alert button:focus {
    outline: none;
    box-shadow: none;
}
.alert button:active {
    outline: none;
    box-shadow: none;
}
a:hover,
a:focus {
    text-decoration: none;
}

#wrapper {
    padding-left: 0;
    transition: all 0.5s ease;
    position: relative;
}

#sidebar-wrapper {
    z-index: 1000;
    position: fixed;
    left: 250px;
    width: 0;
    height: 100%;
    margin-left: -250px;
    overflow-y: auto;
    overflow-x: hidden;
    /* background: #222; */
    background: #d8d5e4;
    transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
    width: 250px;
}

.sidebar-brand {
    position: absolute;
    top: 0;
    width: 250px;
    text-align: center;
    padding: 20px 0;
}

.sidebar-brand h2 {
    margin: 0;
    font-weight: 600;
    font-size: 24px;
    color: #222;
}

.sidebar-nav {
    position: absolute;
    top: 75px;
    width: 250px;
    margin: 0;
    padding: 0;
    list-style: none;
    font-weight: 300;
}

.sidebar-nav>li {
    text-indent: 10px;
    line-height: 42px;
}

.sidebar-nav>li a {
    display: block;
    text-decoration: none;
    /* color: #757575; */
    color: #222;
    font-weight: 600;
    font-size: 15px;
}

.sidebar-nav>li>a:hover,
.sidebar-nav>li.active>a {
    text-decoration: none;
    color: #fff;
    /* background: #F8BE12; */
    background: #674dcf;
}

.sidebar-nav>li>a i.fa {
    font-size: 24px;
    width: 60px;
}

#navbar-wrapper {
    width: 100%;
    position: absolute;
    z-index: 2;
}

#wrapper.toggled #navbar-wrapper {
    position: absolute;
    margin-right: -250px;
}

#navbar-wrapper .navbar {
    border-width: 0 0 0 0;
    background-color: #eee;
    font-size: 24px;
    margin-bottom: 0;
    border-radius: 0;
}

#navbar-wrapper .navbar a {
    color: #757575;
}

#navbar-wrapper .navbar a:hover {
    /* color: #222; */
}

#content-wrapper {
    width: 100%;
    position: absolute;
    padding: 15px;
    top: 100px;
}

#wrapper.toggled #content-wrapper {
    position: absolute;
    margin-right: -250px;
}
.logo{
    color: #000;
}

@media (min-width: 992px) {
    #wrapper {
        padding-left: 250px;
    }

    #wrapper.toggled {
        padding-left: 60px;
    }

    #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 60px;
    }

    #wrapper.toggled #navbar-wrapper {
        position: absolute;
        margin-right: -190px;
    }

    #wrapper.toggled #content-wrapper {
        position: absolute;
        margin-right: -190px;
    }

    #navbar-wrapper {
        position: relative;
    }

    #wrapper.toggled {
        padding-left: 60px;
    }

    #content-wrapper {
        position: relative;
        top: 0;
    }

    #wrapper.toggled #navbar-wrapper,
    #wrapper.toggled #content-wrapper {
        position: relative;
        margin-right: 60px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    #wrapper {
        padding-left: 60px;
    }

    #sidebar-wrapper {
        width: 60px;
    }

    #wrapper.toggled #navbar-wrapper {
        position: absolute;
        margin-right: -250px;
    }

    #wrapper.toggled #content-wrapper {
        position: absolute;
        margin-right: -250px;
    }

    #navbar-wrapper {
        position: relative;
    }

    #wrapper.toggled {
        padding-left: 250px;
    }

    #content-wrapper {
        position: relative;
        top: 0;
    }

    #wrapper.toggled #navbar-wrapper,
    #wrapper.toggled #content-wrapper {
        position: relative;
        margin-right: 250px;
    }
}

@media (max-width: 767px) {
    #wrapper {
        padding-left: 0;
    }

    #sidebar-wrapper {
        width: 0;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #navbar-wrapper {
        position: absolute;
        margin-right: -250px;
    }

    #wrapper.toggled #content-wrapper {
        position: absolute;
        margin-right: -250px;
    }

    #navbar-wrapper {
        position: relative;
    }

    #wrapper.toggled {
        padding-left: 250px;
    }

    #content-wrapper {
        position: relative;
        top: 0;
    }

    #wrapper.toggled #navbar-wrapper,
    #wrapper.toggled #content-wrapper {
        position: relative;
        margin-right: 250px;
    }
}
</style>
