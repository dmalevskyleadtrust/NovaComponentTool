import Tool from './pages/Tool'

Nova.booting((app, store) => {
  console.log("penis");
  Nova.inertia('TestTool', Tool)
})
