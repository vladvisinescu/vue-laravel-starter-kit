import Home from "../../views/Home";
import Hello from "../../views/Hello";
import UsersIndex from "../../views/UsersIndex";

const navRoutes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/hello',
        name: 'hello',
        component: Hello,
    },
    {
        path: '/users',
        name: 'users.index',
        component: UsersIndex,
    },
];

export default navRoutes
