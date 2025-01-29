import React, { useState, useEffect, useRef } from "react";
import { Link } from "react-router-dom"; // Import Link from react-router-dom
import Logo from "./Logo";

const navLinks = [
  { name: "Home", link: "/" },
  { name: "Find Planners", link: "/find-planners" },
  {
    name: "Services",
    link: "/services",
  },
  { name: "About Us", link: "/about-us" },
];

const Nav = () => {
  const [openDropdown, setOpenDropdown] = useState(null);
  const navRef = useRef(null);

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (navRef.current && !navRef.current.contains(event.target)) {
        setOpenDropdown(null);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const handleDropdownToggle = (index) => {
    setOpenDropdown(openDropdown === index ? null : index);
  };

  return (
    <header className="w-full fixed top-0 z-100 bg-[#96BB7C]">
      <nav
        ref={navRef}
        className="flex items-center justify-between p-4 w-full   container mx-auto bg-inherit"
      >
        <Logo />

        <div className="flex space-x-6 text-white">
          {navLinks.map((link, index) => (
            <div key={index} className="relative">
              <Link
                to={link.link}
                className="hover:text-gray-400"
                onClick={() => handleDropdownToggle(index)}
              >
                {link.name}
              </Link>

              {link.subLinks && openDropdown === index && (
                <div className="absolute left-0 w-[200px] space-y-2 bg-white text-black p-2 top-10 rounded-md shadow-md">
                  {link.subLinks.map((subLink, subIndex) => (
                    <Link
                      key={subIndex}
                      to={subLink.link}
                      className="block p-2 hover:text-gray-500"
                    >
                      {subLink.name}
                    </Link>
                  ))}
                </div>
              )}
            </div>
          ))}
        </div>

        <div className="flex space-x-4">
          <button className="text-white border px-4 py-2 rounded-md">
            Contact Us
          </button>
          <button className="text-white bg-[#96BB7C] px-4 py-2 rounded-md hover:bg-green-500">
            Login
          </button>
        </div>
      </nav>
    </header>
  );
};

export default Nav;
