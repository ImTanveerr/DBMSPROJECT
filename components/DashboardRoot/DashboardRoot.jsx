import React from "react";
import { Outlet } from "react-router-dom";
import Sidebar from "../Sidebar/Sidebar";


export default function DashboardRoot() {
  return (
<div className="flex">
      <Sidebar />
      
      <div className="ml-64 p-6 w-full">
        <Outlet /> 
      </div>
    </div>
  );
}
