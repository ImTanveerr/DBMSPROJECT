import React from "react";
import { Link } from "react-router-dom"; // For navigation links

export default function Sidebar() {
  return (
    <div className="w-64 h-screen bg-gray-800 text-white fixed left-0 top-0">
      <div className="p-6">
        <h2 className="text-2xl font-bold text-center text-white">Dashboard</h2>
      </div>
      <nav>
        <ul className="space-y-4 p-4">
          <li>
            <Link
              to="/dashboard/overview"
              className="block py-2 px-4 hover:bg-gray-700 rounded-md"
            >
              Overview
            </Link>
          </li>
          <li>
            <Link
              to="/dashboard/analytics"
              className="block py-2 px-4 hover:bg-gray-700 rounded-md"
            >
              Analytics
            </Link>
          </li>
          <li>
            <Link
              to="/dashboard/settings"
              className="block py-2 px-4 hover:bg-gray-700 rounded-md"
            >
              Settings
            </Link>
          </li>
          <li>
            <Link
              to="/dashboard/users"
              className="block py-2 px-4 hover:bg-gray-700 rounded-md"
            >
              Users
            </Link>
          </li>
          <li>
            <Link
              to="/dashboard/reports"
              className="block py-2 px-4 hover:bg-gray-700 rounded-md"
            >
              Reports
            </Link>
          </li>
        </ul>
      </nav>
    </div>
  );
}
