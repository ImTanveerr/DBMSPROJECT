import React from "react";
import { Link } from "react-router-dom";

const FindRightOne = () => {
  return (
    <section className="bg-white">
      {/* Container */}
      <div className="mx-auto w-full container px-5 py-12 md:px-10 md:py-16 lg:py-20">
        {/* Component */}
        <div className="grid gap-12 sm:gap-20 lg:grid-cols-2">
          {/* Image */}

          <div className="min-h-[530px] overflow-hidden rounded-md bg-gray-100">
            <img src="/public/connect.png" alt="" className="object-cover" />
          </div>

          {/* Content */}
          <div className="flex flex-col items-start gap-2">
            {/* <div className="flex items-center rounded-md bg-gray-300 px-3 py-1">
              <div className="mr-1 h-2 w-2 rounded-full bg-black"></div>
              <p className="text-sm">Available for work</p>
            </div> */}

            {/* Title */}
            <h1 className="mb-6 text-4xl font-bold md:text-6xl text-black lg:mb-8">
              Connect with Top Event
            </h1>
            <h1 className="mb-6 text-4xl font-bold md:text-6xl  text-black lg:mb-8">
              {" "}
              <span className="bg-[#D6E264] text-black px-5 rounded-md">
                Professionals
              </span>
            </h1>
            <p className="text-sm text-black sm:text-xl">
              Plan your perfect event by connecting with skilled and trusted
              professionals all in one place. Whether you need a decorator,
              photographer, or event planner, our platform helps you find and
              collaborate with experts who bring your vision to life.
              {/* From grand
              weddings to corporate gatherings, easily discover the right people
              to handle every detail of your special occasion. */}
            </p>
            <ul className="mt-6 space-y-3 text-sm sm:text-lg text-black">
              <li className="flex items-center">
                <span className="text-green-500 mr-2">✔</span> Access a network
                of verified event professionals.
              </li>
              <li className="flex items-center">
                <span className="text-green-500 mr-2">✔</span> Save time by
                comparing services and prices in one place.
              </li>
              <li className="flex items-center">
                <span className="text-green-500 mr-2">✔</span> Get personalized
                recommendations tailored to your event.
              </li>
              <li className="flex items-center">
                <span className="text-green-500 mr-2">✔</span> Seamlessly
                communicate and manage bookings online.
              </li>
            </ul>

            {/* Buttons */}
            <div className="flex flex-col gap-4 font-semibold sm:flex-row mt-5">
              <Link
                to=""
                className="flex items-center gap-4 rounded-md bg-black px-6 py-3 text-white"
              >
                <p>Find Professionals</p>
              </Link>
              <Link
                to=""
                className="flex gap-4 rounded-md border border-solid border-black px-6 py-3"
              >
                <p>Become a Planner</p>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default FindRightOne;
