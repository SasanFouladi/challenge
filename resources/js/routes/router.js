/**
 * import libraries
 */
import VueRouter from 'vue-router';

/**
 * import components
 */
import Home from '../pages/Home';
import CodeManagement from "../pages/Code/CodeManagement";

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/code-management',
    name: 'CodeManagement',
    component: CodeManagement
  },

];

const router = new VueRouter({
  routes,
  mode: 'hash'
});

export default router;
