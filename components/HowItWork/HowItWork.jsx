import React from "react";
import { Container } from "../Container";

const steps = [
  {
    title: "Create Account",
    description:
      "Sign up by providing your basic information and gain access to our platform. It's quick, easy, and free to get started.",
  },
  {
    title: "Complete Profile",
    description:
      "Fill in your details, add your expertise, and upload a portfolio to let others know what services you offer. A complete profile increases visibility.",
  },
  {
    title: "Get Started",
    description:
      "Start browsing projects, connecting with clients, and offering your event management services. Manage bookings and grow your network.",
  },
];

const HowItWork = () => {
  return (
    <div className="bg-[#244034] ">
      <Container>
        <h2 className="mb-8 text-center text-3xl font-bold md:mb-12 md:text-5xl lg:mb-16 text-white mt-10">
          How It Works
        </h2>
        <img src="/public/step.svg" alt="How it works" />

        {/* Content */}
        <div className="grid gap-5 sm:grid-cols-2 md:grid-cols-3 md:gap-4 lg:gap-6 text-white mt-10">
          {steps.map((step, index) => (
            <div key={index} className="relative p-8 md:p-10">
              <h3 className="mb-4 text-3xl font-bold md:text-5xl text-center">
                {step.title}
              </h3>
              <p className="text-sm text-gray-200">{step.description}</p>
            </div>
          ))}
        </div>
      </Container>
    </div>
  );
};

export default HowItWork;
