import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "./index.css";

import { createBrowserRouter, RouterProvider } from "react-router-dom";
import Root from "./components/Root/Root";
import Home from "./components/Home/Home";
import Signup from "./components/Signup/Signup";
import Login from "./components/Login/Login";
import Demo from "./components/Demo/Demo";
import Services from "./components/Services/Services";
import DashboardRoot from "./components/DashboardRoot/DashboardRoot";
import Dashboard from "./components/Dashboard/Dashboard";
import Events from "./components/Events/Events";

const router = createBrowserRouter([
  {
    path: "/",
    element: <Root/>,
    children: [
      {
        path: "/",
        element: <Home/>,
      },
      {
        path: "/signup",
        element: <Signup />,
      },
      {
        path: "/login",
        element: <Login />,
      },
      {
        path: "/demo",
        element: <Demo />,
      },
      {
        path: "/services",
        element: <Services />,
      },
    ],
  },
  {
    path: "/dashboard",
    element: <DashboardRoot/>,
    children: [
      {
        path: "/dashboard",
        element: <Dashboard/>,
      },
      {
        path: "/dashboard/events",
        element: <Events/>,
      },
    ]
  }
]);

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <RouterProvider router={router} />
  </StrictMode>
);
