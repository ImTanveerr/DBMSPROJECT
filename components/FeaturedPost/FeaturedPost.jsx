import React from "react";
import { Container } from "../Container";
import { Link } from "react-router-dom";

const planners = [
  {
    title: "Wedding Planner",
    description: "Create unforgettable memories with the perfect wedding.",
    category: "Wedding",
    pricing: "$5000 - $10000",
    imageUrl:
      "https://www.southernliving.com/thmb/_DTHAquZBLEHKLIgPi_C3fFIhNo=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/GettyImages-929904308-aeeb687413714dacace50062cece530a.jpg",
    hireUrl: "#", // Link for the Hire button
    viewDetailsUrl: "#", // Link for View Details button
  },
  {
    title: "Birthday Party Planner",
    description:
      "Plan the most fun birthday parties with themed decorations and entertainment.",
    category: "Birthday",
    pricing: "$2000 - $5000",
    imageUrl:
      "https://plus.unsplash.com/premium_photo-1661726486910-7cfff916caad?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmlydGhkYXklMjBjZWxlYnJhdGlvbnxlbnwwfHwwfHx8MA%3D%3D",
    hireUrl: "#", // Link for the Hire button
    viewDetailsUrl: "#", // Link for View Details button
  },
  {
    title: "Photography Services",
    description: "Capture every precious moment with professional photography.",
    category: "Photography",
    pricing: "$1500 - $3000",
    imageUrl:
      "https://media.istockphoto.com/id/506902804/photo/portrait-of-smiling-photographer-with-medium-format-camera.jpg?s=612x612&w=0&k=20&c=IdhqhVmtAHT9lU2-60crMPcnxQZh01w6R-DLFAgUymU=",
    hireUrl: "#", // Link for the Hire button
    viewDetailsUrl: "#", // Link for View Details button
  },
];

const FeaturedPost = () => {
  return (
    <div>
      <h1 className="pt-6 text-4xl font-bold md:text-6xl text-black lg:pt-10 mb-10 text-center">
        Featured Planners
      </h1>
      <Container>
        <div className="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 ">
          {planners.map((planner, index) => (
            <div
              key={index}
              className="rounded overflow-hidden shadow-lg flex flex-col"
            >
              <div className="relative">
                <img
                  className="w-full"
                  src={planner.imageUrl}
                  alt={planner.title}
                />
                <div className="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25"></div>
                <a href="#!">
                  <div className="text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                    {planner.category}
                  </div>
                </a>
              </div>
              <div className="px-6 py-4 mb-auto">
                <a
                  href="#"
                  className="font-medium text-lg hover:text-indigo-600 transition duration-500 ease-in-out inline-block mb-2"
                >
                  {planner.title}
                </a>
                <p className="text-gray-500 text-sm">{planner.description}</p>
                <p className="text-gray-700 text-sm mt-2 font-bold">
                  Pricing: {planner.pricing}
                </p>
              </div>
              <div className="px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                <a
                  href={planner.hireUrl}
                  className="py-2 px-4 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded transition duration-300"
                >
                  Hire
                </a>
                <a
                  href={planner.viewDetailsUrl}
                  className="py-2 px-4 text-xs font-medium text-indigo-600 hover:text-indigo-700 border border-indigo-600 rounded transition duration-300"
                >
                  View Details
                </a>
              </div>
            </div>
          ))}
        </div>
        <div className="flex justify-center mt-10">
          <Link
            to=""
            className="px-8 py-4 text-lg font-medium text-center text-white bg-[#96BB7C] rounded-md "
          >
            View More
          </Link>{" "}
        </div>
      </Container>
    </div>
  );
};

export default FeaturedPost;
