/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const orderRoutes = {
  path: '/order',
  component: Layout,
  name: 'Order',
  meta: {
    permissions: [],
  },
  children: [
    {
        path: 'all',
        component: () => import('@/views/order/index'),
        name: 'Order',
        meta: { title: 'Order', icon: 'el-icon-s-shop', noCache: true },
    },
  ],
};

export default orderRoutes;