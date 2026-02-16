export function BhutanHero() {
  return (
    <div className="relative h-[300px] md:h-[400px] overflow-hidden">
      {/* Background Image with Overlay */}
      <div className="absolute inset-0">
        <img
          src="https://images.unsplash.com/photo-1761048167200-b13ca4252a71?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxQYXJvJTIwdmFsbGV5JTIwQmh1dGFuJTIwbW91bnRhaW5zJTIwYWlycG9ydCUyMHNjZW5pY3xlbnwxfHx8fDE3NzEyMjE2NjB8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
          alt="Paro Valley, Bhutan"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60" />
      </div>

      {/* Hero Content */}
      <div className="relative h-full flex flex-col items-center justify-center text-center px-6 max-w-4xl mx-auto">
        <p className="text-lg md:text-xl text-white/90 max-w-3xl">
          Everything you need to know about travel, visa, accommodation, and logistics for the Summit.
        </p>
      </div>
    </div>
  );
}