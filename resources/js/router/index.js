
import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';
import Items from '../components/Items';
import EditItem from '../components/EditItem';
import AddItem from '../components/AddItem';
import Categories from '../components/Categories';
import EditCategory from '../components/EditCategory';
import AddCategory from '../components/AddCategory';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    }
    ,
    {
        name: 'items',
        path: '/items',
        component: Items
    }
    ,
    {
        name: 'additem',
        path: '/items/add',
        component: AddItem
    },
    {
        name: 'edititem',
        path: '/items/edit/:id',
        component: EditItem
    },
    {
        name: 'categories',
        path: '/categories',
        component: Categories
    }
    ,
    {
        name: 'addcategory',
        path: '/categories/add',
        component: AddCategory
    },
    {
        name: 'editcategory',
        path: '/categories/edit/:id',
        component: EditCategory,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
