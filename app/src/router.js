import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/test',
      name: 'test',
      component: () => import(/* webpackChunkName: "about" */ './views/Test.vue')
    },
    {
      path: '/server',
      alias: '/', //todo: change later to login screen
      name: 'server',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "about" */ './views/Server.vue')
    },
    {
      path: '/database/:database',
      name: 'database',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "about" */ './views/Database.vue'),
      props: true,
    },
    {
      path: '/table/:tableid',
      name: 'table',
      component: () => import(/* webpackChunkName: "about" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/table/:tableid/:column',
      name: 'tablewithcolumn',
      component: () => import(/* webpackChunkName: "about" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/table/:tableid/:column/:value',
      name: 'tablewithcolumnvalue',
      component: () => import(/* webpackChunkName: "about" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/structure/:tableid',
      name: 'structure',
      component: () => import(/* webpackChunkName: "about" */ './views/TableStructure.vue'),
      props: true,
    },
    {
      path: '/editrow/:tableid/:column/:rowid',
      name: 'editrow',
      component: () => import(/* webpackChunkName: "about" */ './views/EditRow.vue'),
      props: true,
    },
    {
      path: '/query',
      name: 'query',
      component: () => import(/* webpackChunkName: "about" */ './views/Query.vue'),
      props: true,
    },
  ]
});

