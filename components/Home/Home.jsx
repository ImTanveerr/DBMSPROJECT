import React from "react";
import Hero from "../Hero/Hero";
import FindRightOne from "../FindRightOne/FindRightOne";
import HowItWork from "../HowItWork/HowItWork";
import FeaturedPost from "../FeaturedPost/FeaturedPost";
import ContactUs from "../ContactUs/ContactUs";
import Banner from "../Banner/Banner";

const Home = () => {
  return (
    <div className="">
      <Hero />
      <Banner />
      <FindRightOne />
      <HowItWork />
      <FeaturedPost />
      <ContactUs />
    </div>
  );
};

export default Home;
