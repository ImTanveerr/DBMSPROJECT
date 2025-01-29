import React from "react";

const Services = () => {
  // Array of feature objects
  const features = [
    {
      imgSrc: "https://www.svgrepo.com/show/530438/ddos-protection.svg",
      title: "Powered by ChatGPT",
      description:
        "The cutting-edge language model that makes interactions a breeze. With its user-friendly interface, effortlessly tap into the world of AI-generated text.",
    },
    {
      imgSrc: "https://www.svgrepo.com/show/530442/port-detection.svg",
      title: "Easy to use",
      description:
        "Simply input your subject, click the generate button, and the result will appear in seconds just like magick.",
    },
    {
      imgSrc: "https://www.svgrepo.com/show/530444/availability.svg",
      title: "Custom settings",
      description:
        "We offer advanced customization. You can freely combine options like roles, languages, publish, tones, lengths, and formats.",
    },
    {
      imgSrc: "https://www.svgrepo.com/show/530440/machine-vision.svg",
      title: "Free trial",
      description:
        "We offer a free trial service without login. We provide many payment options including pay-as-you-go and subscription.",
      link: "/pricing",
    },
    {
      imgSrc: "https://www.svgrepo.com/show/530450/page-analysis.svg",
      title: "90+ templates",
      description:
        "We offer many templates covering areas such as writing, education, lifestyle and creativity to inspire your potential.",
      link: "/templates",
    },
    {
      imgSrc: "https://www.svgrepo.com/show/530453/mail-reception.svg",
      title: "Use Anywhere",
      description:
        "Our product is compatible with multiple platforms including Web, Chrome, Windows and Mac, you can use MagickPen anywhere.",
      link: "/download",
    },
  ];

  return (
    <div className="bg-gray-200 px-2 py-10">
      <div id="features" className="mx-auto max-w-6xl">
        <p className="text-center text-base font-semibold leading-7 text-primary-500">
          Features
        </p>
        <h2 className="text-center font-display text-3xl font-bold tracking-tight text-slate-900 md:text-4xl">
          Writing has never been so easy
        </h2>
        <ul className="mt-16 grid grid-cols-1 gap-6 text-center text-slate-700 md:grid-cols-3">
          {features.map((feature, index) => (
            <li key={index} className="rounded-xl bg-white px-6 py-8 shadow-sm">
              {feature.link ? (
                <a href={feature.link} className="group">
                  <img
                    src={feature.imgSrc}
                    alt=""
                    className="mx-auto h-10 w-10"
                  />
                  <h3 className="my-3 font-display font-medium group-hover:text-primary-500">
                    {feature.title}
                  </h3>
                  <p className="mt-1.5 text-sm leading-6 text-secondary-500">
                    {feature.description}
                  </p>
                </a>
              ) : (
                <>
                  <img
                    src={feature.imgSrc}
                    alt=""
                    className="mx-auto h-10 w-10"
                  />
                  <h3 className="my-3 font-display font-medium">
                    {feature.title}
                  </h3>
                  <p className="mt-1.5 text-sm leading-6 text-secondary-500">
                    {feature.description}
                  </p>
                </>
              )}
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default Services;
