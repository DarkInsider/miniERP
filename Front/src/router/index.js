import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Registration from "../views/Registration";
import Login from "../views/Login";
import Project from  '../views/Project';
import projectCreate from '../views/CreateProject';
import projectEdit from '../views/EditProject';

Vue.use(VueRouter)

  const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
    {
      path: '/reg',
      name: 'Registration',
      component: Registration
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/projectCreate',
      name: 'projectCreate',
      component: projectCreate
    },
    {
      path: '/projectEdit/:id',
      name: 'projectEdit',
      component: projectEdit
    },
    {
      path: '/project/:id',
      name: 'Project',
      component: Project
    },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
